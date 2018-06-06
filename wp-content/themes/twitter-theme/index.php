<!DOCTYPE html>
<html lang="en">

<head>
    <?php do_action('wp_head'); ?>
</head>

<body>


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
<section>
<div class="container">
    <div class="row">
        <div class="col-xs-12 col-lg-6">
            <h2>Топ регіонів:</h2>    
        </div>
    
        <div class="col-xs-12 col-lg-6">
            <div class="top-wrapper display-table w-100">
                <?php
                foreach ($regionsWithTweets as $region):
                    ?>
                    <div class="region" data-region-id="<?=$region->id?>" >
                        <div data-percents="<?=$region->percentage?>">
                            <h4><?= $region->name; ?></h4>
                        </div>
                        <div>
                            <h4><?= $region->tweetNum; ?> <?= $region->tweetNum === 1 ? "tweet" : "tweets" ?></h4>
                        </div>
                    </div>
                <?php
                endforeach;
                ?>
            </div>
            <button class="btn btn-primary w100" role="show-all">
                <span class="for-collapsed">Показувати всі результи</span>
                <span class="for-expanded">Показувати лише найбільщі результати</span>
            </button>
        </div>
        
    </div>
    <div id="histogram1"></div>
    
</div>
<div class="wrap">

</div>
</section>


</body>

</html>