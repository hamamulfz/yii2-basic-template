<body style="background-image: url('<?= $background ?>'); background-size: cover; background-repeat: no-repeat; background-position: center center; font-family: sans-serif;">
    <div style="text-align: center; padding: 60px;">
        <img src="<?= $kcic_logo ?>" style="width: 100px; border-radius: 6px;"><br><br>

        <br>
        <br>
        <div style="display: inline-block; background: #ffffff; border-radius: 6px; padding: 15px; box-shadow: 5px 10px #5e1325;">
            <br>
            <br>
            <br>
            <br>
            <img src="<?= $qr ?>" alt="<?= $name ?> QR Code" style="width: 250px; border-radius: 6px;"><br>
            <br>
            <br>
            <div style="background-color: #881c32; color: #ffffff; display: inline-block; padding: 15px 15px; border-radius: 15px; margin: 10px 30px; font-weight: bold;">
               <strong> <?= strtoupper($name)  ?> </strong>
            </div>
            <div style="margin-top: 20px; font-size: 13px; color: #000000;">
               <strong> Pindai QR Code untuk<br>melaporkan bahaya </strong>
               <br>
               <br>
                <span style="font-style: italic; color: #444444;">
                    Scan the QR Code for<br>report potential hazard
                </span>
            </div>
        </div>
    </div>
</body>
