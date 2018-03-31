<form action="" method="post">
    <input type="hidden" name="category[id]" value="<?= $category->id ?? '' ?>">
    <lable for="categoryname">Enter category name:</lable>
    <input type="text" id="categoryname" name="category[name]" value="<?=$category->name ?? ''?>">
    <input type="submit" name="submit" value="Save">
</form>
