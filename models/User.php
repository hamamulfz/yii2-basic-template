<?php

namespace app\models;

use app\helpers\ServerCheck;
use app\helpers\StatusHelper;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;
use yii\db\Expression;

/**
 * User model
 *
 * @property integer $id
 * @property string $username
 * @property string $password_hash
 * @property string $password_reset_token
 * @property string $verification_token
 * @property string $user_code
 * @property string $email
 * @property string $auth_key
 * @property string $auth_key_ms
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 * @property string $password write-only password
 * @property integer $version_code
 * @property integer $is_onduty
 * @property integer $is_ldap
 */
class User extends ActiveRecord implements IdentityInterface
{
    const STATUS_DELETED = StatusHelper::USER_DELETED; //0;
    const STATUS_INACTIVE = StatusHelper::USER_INACTIVE; //9;
    const STATUS_ACTIVE = StatusHelper::USER_ACTIVE; //10;


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%user}}';
    }

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            ['status', 'default', 'value' => self::STATUS_INACTIVE],
            ['status', 'in', 'range' => [self::STATUS_ACTIVE, self::STATUS_INACTIVE, self::STATUS_DELETED]],
        ];
    }

    static function getInfo($id=null, $email = null)
    {
        if($id == null){
            $user = User::findOne(['email' => $email]);
        } else {
            $user = User::findOne($id);
        }

        if(!$user){
            return null;
        }
        
        $info = [
            'id'        => $user->id,
            'username'  => $user->username,
            'email'     => $user->email,
            'picture'   => $user->picture,
            'user_code' => $user->user_code,
            'full_name' => $user->full_name
        ];
        return json_encode($info);
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        // fungsi untuk mencari user dengan auth_key
        $userToken = UserToken::findOne(['token' => $token, 'status' => 1]);
        if ($userToken) {
            return static::findOne(['id' => $userToken->user_id, 'status' => self::STATUS_ACTIVE]);
        } else {
            return null;
        }
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        return static::findOne(['username' => $username, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * Finds user by password reset token
     *
     * @param string $token password reset token
     * @return static|null
     */
    public static function findByPasswordResetToken($token)
    {
        if (!static::isPasswordResetTokenValid($token)) {
            return null;
        }

        return static::findOne([
            'password_reset_token' => $token,
            'status' => self::STATUS_ACTIVE,
        ]);
    }

    /**
     * Finds user by verification email token
     *
     * @param string $token verify email token
     * @return static|null
     */
    public static function findByVerificationToken($token)
    {
        return static::findOne([
            'verification_token' => $token,
            'status' => self::STATUS_INACTIVE
        ]);
    }

    /**
     * Finds out if password reset token is valid
     *
     * @param string $token password reset token
     * @return bool
     */
    public static function isPasswordResetTokenValid($token)
    {
        if (empty($token)) {
            return false;
        }

        $timestamp = (int) substr($token, strrpos($token, '_') + 1);
        $expire = Yii::$app->params['user.passwordResetTokenExpire'];
        return $timestamp + $expire >= time();
    }

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }

    /**
     * {@inheritdoc}
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * {@inheritdoc}
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return bool if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }

    /**
     * Generates "remember me" authentication key
     */
    public function generateAuthKey()
    {
        $ip_address = ServerCheck::getClientIp();
        $auth_key = Yii::$app->security->generateRandomString();
        $newToken = UserToken::findOne(['user_id' => $this->id, 'ip_address' => $ip_address, 'status' => 1]);
        // var_dump($newToken); die();
        if (!$newToken) {
            $newToken = new UserToken();
            $newToken->user_id = $this->id;
        }
        $newToken->token = $auth_key;
        $newToken->ip_address = $ip_address;
        $newToken->browser = $_SERVER['HTTP_USER_AGENT'];
        $this->auth_key = $auth_key;
        $newToken->save(false);
    }

    /**
     * Generates new password reset token
     */
    public function generatePasswordResetToken()
    {
        $this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    /**
     * Generates new token for email verification
     */
    public function generateEmailVerificationToken()
    {
        $this->verification_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    /**
     * Removes password reset token
     */
    public function removePasswordResetToken()
    {
        $this->password_reset_token = null;
    }

    // menambahkan relasi ke table detail
    /**
     * @return \yii\db\ActiveQuery
     */
    // public function getUserDetail()
    // {
    //     return $this->hasOne(UserDetail::className(), ['user_id' => 'id']);
    // }

    public function getAuthAssignment()
    {
        return $this->hasMany(AuthAssignment::className(), ['user_id' => 'id']);
    }

    public function getUserGroup()
    {
        return $this->hasMany(UserGroup::className(), ['user_id' => 'id']);
    }

    public function getUserBureau()
    {
        return $this->hasOne(UserBureau::className(), ['user_id' => 'id']);
    }

    public $full_url;
    public function fields()
    {
        $fields = parent::fields();
        if (@$this->picture) {
            $this->full_url = Yii::$app->params['storageIntUrl'] . '/' . $this->picture;
        } else {
            $this->full_url = null;
        }
        unset($fields['password_hash'], $fields['password_reset_token']);
        $fields['full_url'] = "full_url";
        
        return $fields;
    }
    public function extraFields()
    {
        return ['userGroup', 'userBureau'];
    }

    public function beforeSave($insert) {
        if (!$this->user_code) {
            $this->user_code = new Expression("md5(concat(username, email, id))");
        }
        return parent::beforeSave($insert);
    }

}
