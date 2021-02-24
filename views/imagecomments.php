<?php
foreach ($this->comments as $comment){
    echo '<p><a href="'.APP_URL.'/user/'.$comment->author_name.'" class="name">'.$comment->author_name.'</a> '.$comment->text."</p>";
}
?>
