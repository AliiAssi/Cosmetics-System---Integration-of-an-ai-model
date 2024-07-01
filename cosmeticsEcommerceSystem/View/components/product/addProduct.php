<head>
    <style>
        .form-container {
            background: #ffffff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            margin: 50px auto;
            max-width: 600px;
        }
        .form-group label {
            font-weight: bold;
            color: #333;
        }
        .btn-primary {
            background: linear-gradient(135deg, #6e8efb, #a777e3);
            border: none;
        }
        .btn-primary:hover {
            background: linear-gradient(135deg, #5c7de8, #906fd4);
        }
    </style>
</head>
<?php 
$categories = getCategoriesForAdmin();
?>
<div class="container">
    <div class="form-container">
        <h2 class="text-center mb-4">ADD PRODUCT</h2>
        <form action="../../routes/productRoute.php" method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label for="productName">Product Name</label>
                <input type="text" name="name" class="form-control" id="productName" placeholder="Enter product name">
            </div>
            <div class="form-group">
                <label for="productDescription">Product Description</label>
                <textarea name="desc" class="form-control" id="productDescription" rows="3" placeholder="Enter product description"></textarea>
            </div>
            <div class="form-group">
                <label for="productPrice">Product Price</label>
                <input type="number" name="price" class="form-control" id="productPrice" placeholder="Enter product price">
            </div>
            <div class="form-group">
                <label for="productDiscount">Product Discount</label>
                <input type="number" name="discount" class="form-control" id="productDiscount" placeholder="Enter product discount">
            </div>
            <div class="form-group">
                <label for="productQuantity">Product Quantity</label>
                <input type="number" name="qty" class="form-control" id="productQuantity" placeholder="Enter product quantity">
            </div>
            <div class="form-group">
                <label for="productCategories">Product Categories</label>
                <select class="form-control" name="categoryId" id="productCategories">
                    <?php foreach ($categories as $category): ?>
                        <option value="<?=$category->_id?>"><?=$category->name;?></option>
                    <?php endforeach;?>
                </select>
            </div>
            <div class="form-group">
                <label for="productImage">Product Image</label>
                <input type="file" name="image" class="form-control-file" id="productImage">
            </div>
            <button name="insert" type="submit" class="btn btn-primary btn-block">ADD</button>
        </form>
    </div>
</div>
