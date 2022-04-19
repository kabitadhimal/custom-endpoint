<?php
use Carbon\Carbon;
$jSkillArray = [];
foreach ($job->jobSkills as $jSkill){
    if($jSkill->name ) {
        $jSkillArray[] = '<span>' . $jSkill->name . '</span>';
    }
}

$closingDate = "";
$postedDate = "";

if( isset($job->postedDate) && !empty($job->postedDate)){
    $postedDate = Carbon::parse($job->postedDate, 'UTC');
    $postedDate =  $postedDate->isoFormat('MMM Do, YY');
}
// $closingDate = $job->closingDate;
if( isset($job->closingDate) && !empty($job->closingDate) ){
    $closingDate = Carbon::parse($job->closingDate, 'UTC');
    $closingDate =  $closingDate->isoFormat('MMM Do, YY');
}

$location = [];
/*$location[] = $job->locationDetails->country;
$location[] = $job->locationDetails->city;
$location[] = $job->locationDetails->state;
implode(', ',$location)
*/
$desc ="";
if(isset($job->description) && !empty($job->description) )  {
    $desc = $job->description;
    $desc = $this->trimWords($desc ,50 ,'...' );
}
$slug = $this->createSlug(esc_attr($job->title));
$jobID = $job->id;
$applyUrl = 'https://jobs.qkly.io/home?id=' . $jobID;
if (isset($job->isExternal) && isset($job->postLink)) {
    $applyUrl = esc_url( $job->postLink );
}
?>
    <div class="qkly-job-card <?php echo $qklyCardClass; ?>">
        <div class="job-title">
            <a  href="#" data-id="#view<?php echo $slug.$count; ?>" class="qkly-modal-open"><?php echo $job->title ; ?></a>
        </div>
        <div class="job-detail">
            <?php
            $companyImg = ( isset($job->profileUrl) && !empty($job->profileUrl) ) ? $job->profileUrl :
                \Qkly\getPluginUrl()."/assets/images/company-placeholder.svg";
            if( isset($companyImg) && ($companyImg ) ):
                ?>
                <div class="company-img">
                    <img src="<?php echo esc_url($companyImg); ?>">
                </div>
            <?php endif; ?>
            <div class="about-company">
            <?php if( isset($job->companyDetails->name) && !empty($job->companyDetails->name) ) : ?>
                <span class="company-name">
                    <i class="qkly-icon icon-building-o"></i>
                      <?php echo $job->companyDetails->name ; ?>
                </span>
            <?php endif; ?>
            <?php if( isset($job->locationDetails->freeFormAddress) && !empty($job->locationDetails->freeFormAddress) ) : ?>
                <span class="company-location">
                    <i class="qkly-icon icon-location"></i>
                    <?php echo $job->locationDetails->freeFormAddress ; ?>
                </span>
            <?php endif; ?>
            </div>
            <?php if( isset($job->jobType) && !empty($job->jobType) ) : ?>
                <div class="job-type job-col">
                    <span class="job-label">Job Type</span>
                    <span class="job-info"><?php echo $job->jobType ; ?></span>
                </div>
            <?php endif; ?>
            <?php if( isset($job->yearsOfExperience) && !empty($job->yearsOfExperience) ) : ?>
            <div class="job-exp job-col">
                <span class="job-label">Years of Experience</span>
                <span class="job-info"><?php echo $job->yearsOfExperience ; ?></span>
            </div>
            <?php endif;
            if( isset($job->minEducationLevel) && !empty($job->minEducationLevel) ) :
            ?>
            <div class="job-educatioon job-col">
                <span class="job-label">Education Level</span>
                <span class="job-info"><?php echo $job->minEducationLevel; ?></span>
            </div>
            <?php endif; ?>

        </div>
        <?php if(!empty($desc)): ?>
            <div class="job-description">
                <span class="title">Job description</span>
                <p class=""><?php echo $desc; ?></p>
            </div>
        <?php endif; ?>
        <?php if(!empty($jSkillArray)): ?>
            <div class="tags">
                <?php echo implode('',$jSkillArray); ?>
            </div>
        <?php endif; ?>
        <div class="job-footer">
            <?php
            $pDate = ( isset($closingDate) && !empty($closingDate) ) ? $closingDate : $postedDate;
            if($pDate):
                $pDate = Carbon::createFromTimeStamp( strtotime($pDate) )->diffForHumans();
                ?>
                <span><?php echo $pDate; ?></span>
            <?php endif; ?>
            <div class="qkly-btn btn-qkly-apply qkly-modal-open" data-id="#view<?php echo $slug.$count?>">View Details</div>
        </div>
    </div>

<?php
/*
 * modal
 */
?>
<div class="qkly-js-modal qkly-modal" id="view<?php echo $slug.$count?>">
    <div class="qkly-modal__content">
        <div class="qkly-job-card qkly-popup">
            <div class="qkly-popup-header">
                <span class="qkly-modal-close">&times;</span>
                <div class="job-title">
                    <a><?php echo $job->title; ?></a>
                </div>
                <div class="job-detail">
                    <?php if( isset($companyImg) && !empty($companyImg) ): ?>
                        <div class="company-img">
                            <img
                                src="<?php echo esc_url( $companyImg ); ?>">
                        </div>
                    <?php endif;
                    if( isset($job->companyDetails->name) && isset($job->locationDetails->freeFormAddress) ): ?>
                    <div class="about-company">
                        <?php if( isset( $job->companyDetails->name ) && !empty($job->companyDetails->name) ): ?>
                        <span class="company-name">
                            <i class="qkly-icon icon-building-o"></i>
                             <?php echo $job->companyDetails->name ; ?></span>
                        <?php endif; ?>

                        <?php if( isset($job->locationDetails->freeFormAddress) && !empty($job->locationDetails->freeFormAddress) ) : ?>
                            <span class="company-location">
                                <i class="qkly-icon icon-location text-danger"></i>
                              <?php echo $job->locationDetails->freeFormAddress ; ?></span>
                        <?php endif; ?>
                    </div>
                    <?php endif; ?>

                    <?php if( isset($job->jobType) && !empty($job->jobType) ) : ?>
                        <div class="job-type job-col">
                            <span class="job-label">Job Type</span>
                            <span class="job-info"><?php echo $job->jobType ; ?></span>
                        </div>
                    <?php endif;
                    if(isset($job->yearsOfExperience) && !empty($job->yearsOfExperience) ) :
                    ?>
                        <div class="job-exp job-col">
                            <span class="job-label">Years of Experience</span>
                            <span class="job-info"><?php echo $job->yearsOfExperience ; ?></span>
                        </div>
                    <?php endif;
                    if( isset($job->minEducationLeve) && !empty($job->minEducationLevel) ) :
                    ?>
                    <div class="job-educatioon job-col">
                        <span class="job-label">Education Level</span>
                        <span class="job-info"><?php echo $job->minEducationLevel ; ?></span>
                    </div>
                    <?php endif; ?>
                    <a href="<?php echo $applyUrl; ?>" class="link-green" target="_blank">Apply on Company Site</a>
                </div>

            </div>

            <?php if( isset($job->description) && !empty($job->description) ) :?>
            <div class="qkly-popup-content">
                <div class="job-description">
                    <span class="title">Job description</span>
                    <?php echo $job->description; ?>
                </div>
            </div>
            <?php
            endif;

            if( isset($job->jobBenefit) && !empty($job->jobBenefit) ):
            ?>
            <div class="qkly-benefits">
                <h2>Benefits</h2>
                <div class="benefits-lists">
                    <?php
                    foreach ( $job->jobBenefit as $jB){
                        $image = $jB->name.".svg";
                        $imageUrl = \Qkly\getPluginUrl().'/assets/images/benefit-icons/'.$image;
                        if($jB->name) {
                        ?>
                        <div class="list-item">
                            <span><img src="<?php echo esc_url( $imageUrl ); ?>" /></span>
                            <h3><?php echo $jB->name ; ?></h3>
                        </div>
                    <?php
                        }
                    }
                    ?>
                </div>
            </div>
            <?php endif; ?>
            <div class="qkly-popup-footer">
               <?php

               if( !empty($jSkillArray)): ?>
            <div class="tags">
                <?php echo implode('',$jSkillArray); ?>
            </div>
            <?php endif; ?>
                <div class="job-footer">
                    <?php  if($pDate) : ?><span><?php echo $pDate; ?></span><?php endif; ?>
                    <a href="<?php echo $applyUrl; ?>" class="link-green" target="_blank">Apply on Company Site</a>
                </div>
            </div>
        </div>
    </div>
</div>