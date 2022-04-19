<?php
namespace Np\Com\Kabitadhimal;
class Pagination{
    public static function paginate($totalPages, $currentPage)
    {
        if($totalPages == 1) return '';

        $pages = [];
        //show two pages before current page
        $startPage = $currentPage - 2;

        //show two pages after current page
        $endPage = $currentPage + 2;
        if($startPage < 1) $startPage = 1;
        if($endPage > $totalPages) $endPage = $totalPages;

        for($page = $startPage; $page<=$endPage; $page++){
            $pages[$page] = ["link"=>$page, "label" =>$page, 'class'=>''];

            if($page == $startPage || $page == $endPage){
                $pages[$page]['label'] = '...';
            }

            if($page == $currentPage){
                $pages[$page]['class'] = 'active';
            }
        }


        //add or override first page
        if(isset($pages[1])){
            $pages[1]['label'] = 1;
        }else{
            $pages[1] = [
                "link"=>1, "label" =>1
            ];
        }

        //add or override last page
        if(isset($pages[$totalPages])){
            $pages[$totalPages]['label'] = $totalPages;
        }else{
            $pages[$totalPages] = [
                "link"=>$totalPages, "label" =>$totalPages
            ];
        }

        /*
         * data-value to update the current page in the url
         *
         */
        ksort($pages);
        ob_start();
        ?>
        <ul class='pagination'>
            <?php
            if($currentPage != 1){
                $previousPage = $currentPage-1;
                 $previousPageLink = get_permalink().'?currentPage='.$previousPage;
                echo "<li class='page-item page-previous'><a class='page-link' data-value='{$previousPage}' href='{$previousPageLink}'><i class='icon icon-arrow-left mx-0'></i>&laquo;</a></li>";
            }
            foreach ($pages as $page){
                $pageLink = get_permalink().'?currentPage='.$page['link'];
                echo "<li class='page-item {$page['class']}'><a class='page-link' data-value='{$page['link']}' href='{$pageLink}'>{$page['label']}</a></li>";
            }

            if($currentPage != $totalPages){
                $nextPage = $currentPage + 1;
                $nextPageLink = get_permalink().'?currentPage='.$nextPage;
                echo "<li class='page-item page-next'><a class='page-link' data-value='{$nextPage}' href='{$nextPageLink}'><i class='icon icon-arrow-right mx-0'></i>&raquo;</a></li>";
            }
            ?>
        </ul>
        <?php
        return ob_get_clean();
    }
}