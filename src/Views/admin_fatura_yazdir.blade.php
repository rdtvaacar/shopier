<!DOCTYPE html>
<!--
This is a starter template page. Use this page to start your new project from
scratch. This page gets rid of all links and provides the needed markup only.
-->
<html>
<head>
    <meta charset="utf-8">
</head>
<body>
@foreach($faturalar as $fatura)
    <?php
    $satir = 1;
    for ($i = 1; $i < 4; $i++) {
    ?>
    <div class="borderEkle"
         style=" min-height: 1070px; float: left; padding: 0; margin: 0; position: relative;  font-weight: 700; width: 679px;">
        <br>
        <div style=" position:  absolute; top:235px; left: 140px;">
            <?php
            echo $fatura->invoice_name . ' ';

            ?>
        </div>
        <div style=" position:  absolute; top:260px; left: 40px;">
            <em><?php echo $fatura->adress; ?></em>
        </div>
        <div style="  position: absolute; left: 80px; top: 355px; font-size: 10pt;"><?php echo (empty($fatura->tax_office)) ? '' : $fatura->tax_office; ?></div>
        <div style="  position: absolute; left: 450px; top: 350px; font-size: 14pt;"><?php echo (empty($fatura->tax_number)) ? $fatura->tc : $fatura->tax_number; ?></div>
        <div style="position: absolute; left: 600px; top:140px; width: 200px;">
            12:00<br>
            <?php
            if ($fatura->tarih == (00 - 00 - 00)) {

            } else {
                echo date('d/m/Y', strtotime($fatura->tarih));
                //  echo '16/09/2017';
            }
            ?><br>
            <?php
            if ($fatura->tarih == (00 - 00 - 00)) {

            } else {
                echo date('d/m/Y', strtotime($fatura->tarih));
                // echo '16/09/2017';
            }
            ?>
        </div>
        <div style="position:  absolute; top:500px; left:30px;float:left; font-size: 14pt; width: 100%;">
            @if(!empty($fatura->cinsi))
                <table width="100%" style="text-align: center">
                    <tr>
                        <td width="40%"><b><?php echo $fatura->cinsi; ?></b></td>
                        <td width="10%">1</td>
                        <td width="25%"><?php echo round(($fatura->fiyat * 100 / 118), 2); ?>₺</td>
                        <td width="25%"><?php echo round(($fatura->fiyat * 100 / 118), 2); ?>₺</td>
                    </tr>
                </table>
            @else
                @foreach($fatura->products as $product)
                    <table width="100%" style="text-align: center">
                        <tr>
                            <td width="40%"><b><?php echo $product->name; ?></b></td>
                            <td width="10%">1</td>
                            <td width="25%"><?php echo $product->fiyat; ?></td>
                            <td width="25%"><?php echo $product->fiyat; ?></td>
                        </tr>
                    </table>

                @endforeach
            @endif
        </div>
        <div style="position:  absolute; top:880px; left: 600px;"><?php echo $fiyat = round($fatura->fiyat * (100 / 118), 2); ?>
            <img width="10" src="/icon/tl.png">
        </div>
        <div style="position:  absolute; top:920px; left: 600px;"><?php echo $fatura->fiyat - $fiyat; ?>
            <img width="10" src="/icon/tl.png">
        </div>
        <div style="clear:both;"></div>
        <img style=" position: absolute; bottom:80px;" width="150" src="/img/iBa.png">
        <div style="position:  absolute; top:960px; left: 600px;"><?php echo $fatura->fiyat; ?>
            <img width="10" src="/icon/tl.png">
        </div>
        <div style="position:  absolute; top:1010px; left: 100px;">#<?php echo $fatura->fiyat_yazi ?>#</div>
    </div>
    <?php
    $satir++;
    }
    ?>
@endforeach
</body>
</html>