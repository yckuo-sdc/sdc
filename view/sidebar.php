<!--siderbar-->
<div class="item">
    <a class="ui logo icon image" href="/"><img src="/images/logo.png"></a>
    <a href="/"><b>SDC ISS</b></a>
</div>

<?php foreach(Menu::ITEM_ARRAY as $menu_item): ?>
    <div class="item">
        <div class="header"><?=$menu_item['header']?></div>
        <div class="menu">
            <?php foreach($menu_item['item'] as $item): ?>
                <a class="item" href="<?=$item['href']?>" target="<?=$menu_item['target']?>"><?=$item['label']?></a>
            <?php endforeach ?>
        </div>
    </div>
<?php endforeach ?>

<a class="item" href="/logout"><b><?=$_SESSION['username'] . "(登出)"?></b></a>
