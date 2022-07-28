    <div class="post">
        <h2 class="ui dividing header">What Is My IP?</h2>
        <div class="ui column center aligned">
            <div class="ui message ">
                <div class="header">Private IP</div>
                <p><?=Ip::get()?></p>
            </div>
            <div class="ui message ">
                <div class="header">Public IP</div>
                <p class="public ip"></p>
                <!--<div class="ui active loader"></div>-->
            </div>
        </div>
    </div><!--End of post-->
