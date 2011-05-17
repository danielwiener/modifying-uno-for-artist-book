Modifying the uno theme by Graph Paper Press <http://graphpaperpress.com> for a site of artist's books.

1. Want text content to appear beneath images on load.  
--Changed one simpler thing - deleted #singlecontent{ display:none; }

2. change thumbnails - larger
-- changed height to 400 on lines 250 and 265 -  gpp_base_image( array( 'width' => '300', 'height' => '400' ) );
-- and changed setting for thumbnails in the Media Settings in Wordpress Admin to 300 by 400 - maybe should add a thumbnail to keep both sizes. ?? 