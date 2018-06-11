<div class="list-group">

        <?php foreach ($informations as $information) { ?>

                <a href="<?php echo $information['href']; ?>" class="list-group-item"><?php echo $information['title']; ?></a>

        <?php } ?>

            <a href="<?php echo $contact; ?>" class="list-group-item"><?php echo $text_contact; ?></a>

            <a href="<?php echo $sitemap; ?>" class="list-group-item"><?php echo $text_sitemap; ?></a>
        </li>
    </ul>
</div>