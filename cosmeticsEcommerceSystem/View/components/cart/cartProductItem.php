<?php
function generateProductItemDiv($product , $qty) {
    $html = '<div class="d-flex flex-row justify-content-between align-items-center p-2 bg-white mt-4 px-3 rounded" id="item'.$product->getId().'">';
    $html .= '<div class="mr-1"><img class="rounded" src="../uploaded_img/' . $product->getPicture() . '" width="70" alt = "'.$product->getPicture().'"></div>';
    $html .= '<div class="d-flex flex-column align-items-center product-details"><span class="font-weight-bold" style="color : brown">' . $product->getName() . '</span>';
    $html .= '<div class="d-flex flex-row product-desc">';
    $html .= '<div class="size mr-1"><span class="text-grey">description:</span><span class="font-weight-bold">&nbsp;' . $product->getDescription() . '</span></div>';
    $html .= '</div></div>';
    $html .= '<div class="d-flex flex-row align-items-center qty"><i class="fa fa-minus text-danger"  onclick="decrementItem('.$product->getId().')"></i>';
    $html .= '&nbsp;&nbsp;&nbsp;<h5 class="text-grey mt-1 mr-1 ml-1"> <b id="qty'.$product->getId().'">' . $qty . '</b> &nbsp;&nbsp;&nbsp;</h5><i class="fa fa-plus text-success"  onclick="incrementItem('.$product->getId().')"></i>';
    $html .= '</div>';
    $html .= '<div>';
    $html .= '<h5 class="text-grey">$' . number_format($product->getPrice() - ($product->getPrice() * $product->getDiscount() / 100), 2) . '</h5>';
    $html .= '</div>';
    $html .= '<div class="d-flex align-items-center mb-1"><a class="btn btn-danger" onclick="deleteItem('.$product->getId().')" style="color:white;">delete</a></div>';
    $html .= '</div>';
    
    return $html;
}
