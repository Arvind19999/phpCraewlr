    <?php
    require "vendor/autoload.php";
    use Symfony\Component\DomCrawler\Crawler;

    $url = "https://books.toscrape.com/catalogue/category/books/science_22/index.html";
    $client =  new \GuzzleHttp\Client();
    $res = $client->request("GET",$url);
    $html = "".$res->getBody();
    $crawler = new Crawler($html);
    $crawler->filter('body');
    // print_r($crawler);
    function random_strings($length_of_string)
    {
    
        // String of all alphanumeric character
        $str_result = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
    
        // Shuffle the $str_result and returns substring
        // of specified length
        return substr(str_shuffle($str_result),
                        0, $length_of_string);
    }
    $z= array();
    $nodeValues = $crawler->filter('.product_pod')->each(function (Crawler $node, $i) {
        $id = random_strings(8);
        $category = "Science";
        $categoryUrl = "https://books.toscrape.com/catalogue/category/books/science_22/index.html";
        $title = $node->filter("h3 a")->text();
        $price = $node->filter(".product_price p")->text();
        $inStock = $node->filter(".instock")->text();

        $rating_class = $node->filter("article p")->attr("class");    
        $rating = explode(" ",$rating_class);
        $imageUrl  = $node->filter(".image_container a")->attr("href");
        // $detailUrl = "https://books.toscrape.com/catalogue".$imageUrl;
        $detailUrl = str_replace("../../../","https://books.toscrape.com/catalogue/",$imageUrl);
        // $imageUrl  = $node->filter(".image_container a")->attr("href");
    
        $item = [
            "id"=>$id,
            "category" =>$category,
            "categoryUrl"=>$categoryUrl,
            "title"=>$title,
            "price"=>$price,
            "inStock"=>$inStock,
            "rating"=>$rating[1],
            "detailUrl"=>$detailUrl
                ];
        return $item;
            });
            echo "<pre>";
    print_r($nodeValues);

    $csv = 'science_listing.csv';
       
    // File pointer in writable mode
    $file_pointer = fopen($csv, 'w');
       
    // Traverse through the associative
    // array using for each loop
    $csv_header = array("ID","Category","CategoryUrl","Title","Price","InStock","Rating","DetailUrlImg");
    fputcsv($file_pointer,$csv_header);
    foreach($nodeValues as $i){
        $csv_data = array(
            "ID" => $i["id"],
            "Category" => $i["category"],
            "CategoryUrl" => $i["categoryUrl"],
            "Title"=> $i["title"],
            "Price" => $i["price"],
            "InStock" => $i["inStock"],
            "Rating" => $i["rating"],
            "DetailUrlImg" => $i["detailUrl"]
          );
        // Write the data to the CSV file
        fputcsv($file_pointer, $csv_data);
    }
    // Close the file pointer.
    fclose($file_pointer);
            ?>