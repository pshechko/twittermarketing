<!DOCTYPE html>
<html lang="en">

<head>
    <?php do_action('wp_head'); ?>
</head>

<body>

<div class="flex-wrapper horizontal-align-center" id="map-header">
    <div class="map-wrapper" role="map-wrapper">
        <!--<div class="region-circle" style="
            top: 66px;
            left: 160px;
            width: 117px;
            height: 117px;
        "></div>
        <div class="region-circle" style="
            top: 129px;
            left: 238px;
            width: 70px;
            height: 70px;
        "></div><div class="region-circle" style="
            top: 159px;
            left: 186px;
            width: 70px;
            height: 70px;
        "></div>-->
    </div>
</div>

<section class="main">
    <div class="container">
        <div class="row">
            <div class="col-xs-12 col-lg-6">
                <h2>Топ регіонів:</h2>
            </div>

            <div class="col-xs-12 col-lg-6">
                <div class="top-wrapper display-table w-100 expanded">
                    <?php
                    foreach ($regionsWithTweets as $region):
                        ?>
                        <div class="region" data-region-id="<?= $region->id ?>">
                            <div data-percents="<?= $region->percentage ?>">
                                <h4><?= $region->name; ?></h4>
                            </div>
                            <div>
                                <span><?= $region->percentage; ?>%</span>
                            </div>
                        </div>
                    <?php
                    endforeach;
                    ?>
                </div>
                <button class="btn btn-primary w100" role="show-all">
                    <span class="for-collapsed">Показувати всі результи</span>
                    <span class="for-expanded">Показувати лише найбільші результати</span>
                </button>
            </div>

        </div>
        <div id="histogram1"></div>

    </div>
    <div class="wrap">

    </div>
</section>



<?php
foreach ($regions as $region):
    ?>
    
    <div class="modal fade" id="modal-region-<?=$region->id;?>" tabindex="-1" role="dialog"
         aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title" id="exampleModalLongTitle"><?=$region->name;?></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="">
                        <div id="line-chart-1-region-<?=$region->id;?>"></div>
                    </div>
                    <div class="">
                        <div id="column-chart-1-region-<?=$region->id;?>"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Закрити</button>
                </div>
            </div>
        </div>
    </div>
<?php
endforeach;
?>

 <div class="modal fade" id="modal-region-compare" tabindex="-1" role="dialog"
         aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Порівняння</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class='region-select-wrapper'>
                        <ul class='region-select flex-wrapper horizontal-align-space-between flex-wrap-wrap'>
                        <?php
                        foreach ($regions as $region):
                            ?>
                            <li>
                                <input type="checkbox" 
                                    value="<?=$region->id;?>" 
                                    id="region-select-item-<?=$region->id;?>"
                                    name="region-select-item"/>
                                <label for="region-select-item-<?=$region->id;?>">
                                <button 
                                    type="button" 
                                    class="btn btn-primary" 
                                    data-toggle="tooltip" 
                                    data-placement="top" 
                                    title="<?=$region->name;?>">
                                        <?=$region->label;?>
                                </button>
                                </label>
                            </li>
                            <?php
                        endforeach;
                        ?>
                        </ul>
                    </div>
                    <div class="">
                        <div id="line-chart-1-region-compare"></div>
                    </div>
                    <div class="">
                        <div id="column-chart-1-region-compare"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Закрити</button>
                </div>
            </div>
        </div>
    </div>

    <button type="button" 
        data-target="#modal-region-compare"
        data-toggle="modal"
        class="btn btn-primary"
        id="show-compare">
        <span class="glyphicon glyphicon-transfer"></span>
    </button>

</body>

</html>