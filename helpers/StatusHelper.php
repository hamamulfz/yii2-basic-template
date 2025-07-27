<?php

/**
 * User: Taufiq Rahman (Rahman.taufiq@gmail.com)
 * Date: 26/09/20
 * Time: 07.01
 */

namespace app\helpers;

class StatusHelper
{
    //--User Standby
    const JOBS_ON_WEB = 2;
    const JOBS_UPLOAD_IMAGE = 1;

    //--CONST STATUS USER
    const USER_DELETED = 0;
    const USER_INACTIVE = 9;
    const USER_ACTIVE = 10;
    const USER_LOCKED = 20;

    //--CONST STATUS DATA
    const INACTIVE   = 0; //DELETE
    const ACTIVE     = 1;
    const DISABLE    = 2;

    //--CONST STATUS
    const INPUT_MANUAL = 1;
    const INPUT_NORMAL = 0;

    // --CONST STATUS_READ Notification
    //$read_status 0 = unread, 1 = read
    const NOTIFICATION_UNREAD = 0;
    const NOTIFICATION_READ = 1;
    const NOTIFICATION_CONFIRM = 2;

    // --CONST BROADCAST
    const BROADCAST_POST = 1;
    const BROADCAST_DRAFF = 0;
    const BROADCAST_DROP = 2;

    //--CONST Approve
    const UNAPPROVED = 0;
    const APPROVE = 1;
    const REJECT = 2;

    //report_status_active, file_status_active
    const LP_REJECTED = 9;
    const LP_DELETED = 0;
    const LP_UNREVIEWED = 1; //default
    const LP_REVIEWED = 2;
    const LP_INPROGRESS = 3;
    const LP_CLOSED = 4;
    const LP_BACK = 5;
    const LP_ADM_CLOSED = 6;

    //publicfile_status_active
    const PF_REJECTED = 9;
    const PF_DELETED = 0;
    const PF_UNREVIEWED = 1; //default
    const PF_REVIEWED = 2;
    const PF_PHISIC_DELETED = 3;

}
