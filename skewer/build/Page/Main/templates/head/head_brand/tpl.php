<?php

namespace skewer\build\Page\Main\templates\head\head_brand;

use skewer\base\section\Page;
use skewer\components\design\Design;
use skewer\components\seo;

/*
 * @var int $mainId
 */

/*
 * @var $minicartHead
 */

Asset::register($this);

//var_dump( 'head_brand' );

?>

<div class="l-header">
    <div class="header__wrapper js_dnd_wraper">

        <div class="b-fixed-head hide-on-tablet hide-on-mobile js-fixedhead">
            <div class="fixed-head__inner">
                <div class="fixed-head__logo">
                    <a href="/"></a>
                </div>
                <?= (isset($layout['fixed_menu'])) ? $layout['fixed_menu'] : ''; ?>
            </div>
        </div>

       <div class="b-pilot">
            <div class="b-logo <?php if (Design::modeIsActive()): ?> g-ramaborder js-designDrag-<?= Design::get('page.head.logo', 'h_position'); ?><?php endif; ?>"<?= Design::write(' sktag="page.head.logo"'); ?>><a href="<?= '[' . $mainId . ']'; ?>"><img alt="<?= $site_name['value'] ?? ''; ?>" src="<?= Design::getLogo(); ?>"></a>
                <?php if (Design::modeIsActive()): ?>
                    <div class="b-desbtn"><span>logo</span><ins></ins></div>
                <?php endif; ?>
            </div>

            <div class="pilot__1 hide-on-tablet hide-on-mobile<?php if (Design::modeIsActive()): ?> g-ramaborder js-designDrag-<?= Design::get('page.head.pilot1', 'h_position'); ?>" sktag="page.head.pilot1" skeditor="headtext1/source<?php endif; ?>"><?= Page::getShowVal('headtext1', 'source'); ?>
                <?php if (Design::modeIsActive()): ?>
                    <div class="b-desbtn"><span>txt1</span><ins></ins></div>
                <?php endif; ?>
            </div>

            <div class="pilot__2 <?php if (Design::modeIsActive()): ?> g-ramaborder js-designDrag-<?= Design::get('page.head.pilot2', 'h_position'); ?>" sktag="page.head.pilot2" skeditor="headtext2/source<?php endif; ?>"><?= Page::getShowVal('headtext2', 'source'); ?>
                <?php if (Design::modeIsActive()): ?>
                    <div class="b-desbtn"><span>txt2</span><ins></ins></div>
                <?php endif; ?>
            </div>

            <div class="pilot__3 <?php if (Design::modeIsActive()): ?> g-ramaborder js-designDrag-<?= Design::get('page.head.pilot3', 'h_position'); ?>" sktag="page.head.pilot3" skeditor="headtext3/source<?php endif; ?>"><?= Page::getShowVal('headtext3', 'source'); ?>
                <?php if (Design::modeIsActive()): ?>
                    <div class="b-desbtn"><span>txt3</span><ins></ins></div>
                <?php endif; ?>
            </div>

            <div class="pilot__4 <?php if (Design::modeIsActive()): ?> g-ramaborder js-designDrag-<?= Design::get('page.head.pilot4', 'h_position'); ?>" sktag="page.head.pilot4" skeditor="headtext4/source<?php endif; ?>"><?= Page::getShowVal('headtext4', 'source'); ?>
                <?php if (Design::modeIsActive()): ?>
                    <div class="b-desbtn"><span>txt4</span><ins></ins></div>
                <?php endif; ?>
            </div>

            <div class="pilot__5 <?php if (Design::modeIsActive()): ?> g-ramaborder js-designDrag-<?= Design::get('page.head.pilot5', 'h_position'); ?>" sktag="page.head.pilot5" skeditor="headtext5/source<?php endif; ?>"><?= Page::getShowVal('headtext5', 'source'); ?>
                <?php if (Design::modeIsActive()): ?>
                    <div class="b-desbtn"><span>txt5</span><ins></ins></div>
                <?php endif; ?>
            </div>
        </div>

        <?= (isset($layout['head'])) ? $layout['head'] : ''; ?>

        <div class="header__left"></div>
        <div class="header__right"></div>
    </div>
</div>