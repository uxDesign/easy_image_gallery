<?php defined('C5_EXECUTE') or die("Access Denied.")?>
	<?php if (isset($tagsObject) && isset($tagsObject->tags) && count($tagsObject->tags) && isset($options->filtering)) : ?>
		<ul class="filter-set" data-filter="filter" id="filter-set-<?php echo $bID?>">
		  <li><a href="#show-all" data-option-value="*" class="selected rounded"><?php echo t('show all')?></a></li>
		  <?php foreach ($tagsObject->tags as $handle =>$tag): ?>
		  <li><a class="" href="#<?php echo $handle?>" data-filter=".<?php echo $handle ?>"><?php echo $tag?></a></li>
		  <?php endforeach ?>
		</ul>
	<?php      endif ?>
