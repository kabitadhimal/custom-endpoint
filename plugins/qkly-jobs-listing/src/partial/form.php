<div class="qkly-content" style="" >
<form name="filterForm" method="post"  id="qkly-job-search">
    <div class="qkly-filter">
        <div class="filter-container">
            <div class="job-title">
                <div class="input">
                    <input type="text"
                           name="searchText"
                           id="qkly-searchText"
                           placeholder="Job title, skills, keywords, or company"
                           value="<?php echo ( isset($inputs['searchText']) && !empty($inputs['searchText']) ) ? $inputs['searchText'] : ""; ?>" >
                </div>
            </div>
            <div class="job-location">
                <div class="input">
                    <input type="text"
                           name="location"
                           class="typeahead"
                           id="qkly-location"
                           placeholder="Location"
                           value="<?php echo ( isset($inputs['location']) && !empty($inputs['location']) ) ? $inputs['location'] : "" ; ?>"
                    >
                </div>
            </div>
            <div class="slide-select">
                <div class="remote">
                    <span class="section-label">Remote</span>
                    <div class="qkly-custom-switch custom-control">
                        <input type="checkbox"
                               name="isRemote"
                               class="custom-control-input"
                               value="yes"
                               id="customSwitch1"
                               [checked]="isRemote"
                               (change)="toggleRemote($event)"
                            <?php echo ( isset($inputs['isRemote']) && !empty($inputs['isRemote']) ) ? "checked" : "" ?>
                        >
                        <label class="custom-control-label" for="customSwitch1"></label>
                    </div>
                </div>
                <div class="remote">
                    <span class="section-label">Listed Salary</span>
                    <div class="qkly-custom-switch custom-control">
                        <input type="checkbox"
                               name="isSalary"
                               value="yes"
                               class="custom-control-input"
                               id="customSwitch2"
                               [checked]="isRemote"
                               (change)="toggleRemote($event)"
                            <?php echo ( isset($inputs['isSalary'] ) &&  !empty($inputs['isSalary']) ) ? "checked" : "" ?>
                        >
                        <label class="custom-control-label"
                               for="customSwitch2"></label>
                    </div>
                </div>
            </div>
            <div class="advance-search">
                <button class="qkly-btn btn-qkly-search" type="submit">Search</button>
            </div>
        </div>
        
    </div>
</form>

<div class="qkly-not-found">
    <img src="<?php echo \Qkly\getPluginUrl()?>/assets/images/no-matching-job.svg" alt="No Matching Jobs Found">
    <span class="qkly-not-found-text">No Jobs founds!</span>
</div>
    <?php
    $option = get_option('qkly_option_name');
    $gridType = isset($option['layout_display'] ) && !empty($option['layout_display']) ? $option['layout_display'] : "";
    $qklyListClass ="";
    $qklyCardClass = "";
    if($gridType == "single-col") {
        $qklyListClass = "qkly-single-list";
    }
    ?>
<div class="qkly-js-result qkly-job-list <?php echo $qklyListClass; ?>"></div>
    <nav aria-label="Pagination">
        <div class="qkly-js-pagination"></div>
    </nav>
</div>

<script>
    (function(){
        var initialData = <?php echo json_encode($inputs); ?>;
        var $jobsForm = document.getElementById('qkly-job-search');
        var jobSearch = new QklyJobSearch(
            "<?php echo get_the_permalink(); ?>",
            "<?php echo admin_url('admin-ajax.php'); ?>",
            initialData
        );
        // 2. Get a reference to our preferred element (link/button, see below) and
        //    add an event listener for the "click" event.

        jobSearch.addEventsToPagination();

        $jobsForm.addEventListener('submit', function(event){
            event.preventDefault();

            var locationVal = $jobsForm.querySelector("[name='location']").value;
            var searchTextVal = $jobsForm.querySelector("[name='searchText']").value;
            var isRemote = $jobsForm.querySelector("[name='isRemote']").checked;
            isRemote = (isRemote) ? 1 : 0;
            var isSalary = $jobsForm.querySelector("[name='isSalary']").checked;
            isSalary = (isSalary) ? 1 : 0;

            jobSearch.update('location', locationVal,true);
            jobSearch.update('searchText', searchTextVal,true);
            jobSearch.update('isRemote', isRemote,true);
            jobSearch.update('isSalary', isSalary,true);
            /*
            listingApp.update('location', document.getElementById('location').value,true);
            listingApp.update('searchText', document.getElementById('searchText').value,true);

            var isRemote = (document.getElementById('isRemote').checked) ? 1 : 0;
            listingApp.update('isRemote',isRemote, true);

            var isSalary = (document.getElementById('isSalary').checked) ? 1 : 0;
            listingApp.update('isSalary',isSalary, true);
            */
            jobSearch.submit();
        });
        jobSearch.submit();
    })();
</script>