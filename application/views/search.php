<div id="search" data-role="page">

	<div data-role="header" data-theme="b">
		<h1>When Bus ?</h1>
        <a href="/about" data-role="button" data-icon="info"
           class="ui-btn-right info-button" data-rel="dialog">Инфо</a>
	</div>

	<div data-role="content">
        <input type="search" class="search" value="" data-timeout="500" placeholder="Введите название остановки"/>
        <div class="didyoumean ui-body ui-body-e" style="display:none;">
            Вы имели в виду "<span class="fixed"></span>" ?
        </div>

        <ul class="search-results" data-role="listview" data-inset="true">
        <?=isset($content) ? $content : ''?>
        </ul>

        <div class="standalone-ad ui-body ui-body-e" style="display:none">
            <p>Нажмите <nobr><img src="/img/ios_share_btn.png" style="height:16px"/>&rarr;Добавить в Домой,</nobr> чтобы закрепить ярлык на главном экране</p>
        </div>
	</div>
</div>