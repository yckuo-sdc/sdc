<!--footer-->
		<footer id="footer" class="container">
            <div class="sitemap">
                <div class="ui horizontal list">
                    <?php foreach(Menu::ITEM_ARRAY as $menu_item): ?>
                        <div class="item">
                            <div class="header"><?=$menu_item['header']?></div>
                            <div class="ui list">
                                <?php foreach($menu_item['item'] as $item): ?>
                                    <a class="item" href="<?=$item['href']?>" target="<?=$menu_item['target']?>"><?=$item['label']?></a>
                                <?php endforeach ?>
                            </div>
                        </div>
                    <?php endforeach ?>
                </div>
            </div>

			<p>COPYRIGHT &copy; TainanGov SDC. All rights reserved. Design by <a href="#">yckuo</a>.</p>
		<footer>
		</div>
	</div>
</div>
</body>
</html>
