var QklyModal = {
    open: function($modal){
        $modal.classList.add('active');
        document.body.classList.add("qkly-prevent-scroller");
        /* adding class to html tag */
        var root = document.documentElement;
        root.classList.add("qkly-prevent-scroller");
    },
    close: function(){
        var $modals = document.querySelectorAll('.qkly-modal.active');
        if($modals.length){
            $modals.forEach(function($modal){
                $modal.classList.remove('active');
                var root = document.documentElement;
                root.classList.remove("qkly-prevent-scroller");
                document.body.classList.remove("qkly-prevent-scroller");
            });
        }
    }
};