<body style="background-size: cover; background-repeat: no-repeat; background-position: center center; font-family: sans-serif;">
    <div style="text-align: center; padding: 60px;">
        <img src="<?= $kcic_logo ?>" style="width: 100px; border-radius: 6px;"><br><br>
        <img src="<?= $qr ?>" alt="<?= $name ?> QR Code" style="width: 500px; border-radius: 6px;"><br>
        <div style=" color: #000000; display: inline-block; padding: 15px 15px; border-radius: 15px; margin: 10px 30px; ">
            <strong style="font-weight: bold;"> <?= strtoupper($name)  ?> </strong><br>
            <?= $type  ?> <br>
           <strong> <?= $bureau  ?> </strong>
        </div>
        <div style="margin-top: 10px; font-size: 13px; color: #000000;">
            <strong> Pindai QR Code untuk<br>inspeksi peralatan </strong>
            <br>
            <br>
            <span style="font-style: italic; color: #444444;">
                Scan the QR Code for<br>equipment inspection
            </span>
        </div>
    </div>
</body>
