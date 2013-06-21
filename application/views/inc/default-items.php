<?php
// this is a placeholder for correct etag generation on empty default-list requests
echo "<!-- default-items -->";

if(isset($nearest) && count($nearest)){
    printf('<li data-role="list-divider">Ближайшие</li>');
    echo View::factory('inc/search-items')->set('items',$nearest);
}
if(isset($favorite) && count($favorite)){
    printf('<li data-role="list-divider" data-icon="star">Избранные</li>');
    echo View::factory('inc/search-items')->set('items',$favorite);
}
if(isset($popular) && count($popular)){
    printf('<li data-role="list-divider" data-icon="star">Популярные</li>');
    echo View::factory('inc/search-items')->set('items',$popular);
}
?>