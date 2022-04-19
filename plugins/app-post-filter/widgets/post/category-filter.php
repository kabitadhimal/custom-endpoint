<section class="blog-list-filter">
    <div class="container">
        <div class="d-flex flex-wrap align-items-center justify-content-between">
            <form class="form-inline" id="app-filter" action="<?=$pagePermalink?>" >
            <div class="radio-toolbar app-radio">

                <input type="radio" id="all" name="category" value="all" <?=isset( $_GET['category'] ) && ( $_GET['category']=='all' ) ? "checked" : ""?> >
                <label for="all">All</label>
                <?php
                $categories = get_terms( 'category', [ 'parent' => 0 ] );
                foreach($categories as $category):
                   // $category_link = get_category_link( $category->term_id );
                    $categorySlug = $category->slug;
                    $checked = isset( $_GET['category'] ) && ( $_GET['category']==$categorySlug ) ? "checked" : "";
                    ?>
                    <input type="radio" id="<?=$categorySlug?>" name="category" value="<?=$categorySlug?>" <?=$checked?> >
                    <label for="<?=$categorySlug?>"><?php echo $category->name; ?></label>
                <?php endforeach; ?>
            </div>
            </form>

            <form class="form-inline" action="<?=$pagePermalink?>" >
            <div class="search">
                    <input class="form-control"
                           name="search"
                           type="search"
                           value="<?=isset( $_GET['search'] ) && ( !empty( $_GET['search'] ) ) ? $_GET['search'] : ""?>"
                           placeholder="Search"
                           aria-label="Search">
            </div>
            </form>

        </div>
    </div>
</section>