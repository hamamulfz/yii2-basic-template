<div style="position: relative; width: 100%; height: 100%; text-align: center; font-family: sans-serif;">

        <!-- Background image -->
        <div style="position: absolute; top: 0; left: 0; width: 100%; height: 100%;">
            <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTnZ9Gu42jKvCLoY9wQcbiotAUkepkSqggtQg&s" style="width: 100%; height: auto;" alt="Background">
        </div>

        <!-- Foreground content -->
        <div style="position: relative; padding: 40px;">
            <img src="https://upload.wikimedia.org/wikipedia/en/3/3c/PT_KCIC_logo.png" style="width: 100px; border-radius: 6px;">
            <br>
            <br>
            <div style="display: inline-block; background: #ffffff; border-radius: 6px; padding: 15px; box-shadow: 5px 10px #5e1325;">
                <img src="<?= $qr ?>" alt="<?= $name ?> QR Code" style="width: 250px; border-radius: 6px;"><br>

                <div style="background-color: #881c32; color: #ffffff; display: inline-block; padding: 5px 15px; border-radius: 15px; margin-top: 10px; font-weight: bold;">
                    <?= $name ?>
                </div>

                <div style="margin-top: 10px; font-size: 13px; color: #000000;">
                    Pindai QR Code untuk<br>melaporkan bahaya
                    <br>
                    <br>
                    <span style="font-style: italic; color: #444444;">
                        Scan the QR Code for<br>report potential hazard
                    </span>
                </div>
            </div>
        </div>
    </div>
