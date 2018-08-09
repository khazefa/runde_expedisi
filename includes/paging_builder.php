<?php
/**
 * @link: http://www.Awcore.com/dev
 */
   function pagination($statement, $per_page = 10,$page = 1, $url = '?'){
        $database = DB::getInstance();
    	$query = "SELECT 1 FROM {$statement}";
    	$rows = $database->num_rows( $query );
    	$total = $rows;
        $adjacents = "2"; 

    	$page = ($page == 0 ? 1 : $page);  
    	$start = ($page - 1) * $per_page;								
		
    	$prev = $page - 1;							
    	$next = $page + 1;
        $lastpage = ceil($total/$per_page);
    	$lpm1 = $lastpage - 1;
    	
    	$pagination = "";
    	if($lastpage > 1)
    	{	
    		$pagination .= "<ul class='pagination'>";
                $pagination .= "<li class='page-item disabled'><a class='page-link'>Page $page of $lastpage</a></li>";
    		if ($lastpage < 7 + ($adjacents * 2))
    		{	
    			for ($counter = 1; $counter <= $lastpage; $counter++)
    			{
    				if ($counter == $page)
    					$pagination.= "<li class='page-item active'><a class='page-link'>$counter</a></li>";
    				else
    					$pagination.= "<li class='page-item'><a href='{$url}page=$_GET[page]&nav=$counter' class='page-link'>$counter</a></li>";			
    			}
    		}
    		elseif($lastpage > 5 + ($adjacents * 2))
    		{
    			if($page < 1 + ($adjacents * 2))		
    			{
    				for ($counter = 1; $counter < 4 + ($adjacents * 2); $counter++)
    				{
    					if ($counter == $page)
    						$pagination.= "<li class='page-item active'><a class='page-link'>$counter</a></li>";
    					else
    						$pagination.= "<li class='page-item'><a href='{$url}page=$_GET[page]&nav=$counter' class='page-link'>$counter</a></li>";					
    				}
    				$pagination.= "<li class='page-item dot'>...</li>";
    				$pagination.= "<li class='page-item'><a href='{$url}page=$_GET[page]&nav=$lpm1' class='page-link'>$lpm1</a></li>";
    				$pagination.= "<li class='page-item'><a href='{$url}page=$_GET[page]&nav=$lastpage' class='page-link'>$lastpage</a></li>";		
    			}
    			elseif($lastpage - ($adjacents * 2) > $page && $page > ($adjacents * 2))
    			{
    				$pagination.= "<li class='page-item'><a href='{$url}page=$_GET[page]&nav=1' class='page-link'>1</a></li>";
    				$pagination.= "<li class='page-item'><a href='{$url}page=$_GET[page]&nav=2' class='page-link'>2</a></li>";
    				$pagination.= "<li class='page-item dot'>...</li>";
    				for ($counter = $page - $adjacents; $counter <= $page + $adjacents; $counter++)
    				{
    					if ($counter == $page)
    						$pagination.= "<li class='page-item active'><a class='page-link'>$counter</a></li>";
    					else
    						$pagination.= "<li class='page-item'><a href='{$url}page=$_GET[page]&nav=$counter' class='page-link'>$counter</a></li>";					
    				}
    				$pagination.= "<li class='page-item dot'>..</li>";
    				$pagination.= "<li class='page-item'><a href='{$url}page=$_GET[page]&nav=$lpm1' class='page-link'>$lpm1</a></li>";
    				$pagination.= "<li class='page-item'><a href='{$url}page=$_GET[page]&nav=$lastpage' class='page-link'>$lastpage</a></li>";		
    			}
    			else
    			{
    				$pagination.= "<li class='page-item'><a href='{$url}page=$_GET[page]&nav=1' class='page-link'>1</a></li>";
    				$pagination.= "<li class='page-item'><a href='{$url}page=$_GET[page]&nav=2' class='page-link'>2</a></li>";
    				$pagination.= "<li class='page-item dot'>..</li>";
    				for ($counter = $lastpage - (2 + ($adjacents * 2)); $counter <= $lastpage; $counter++)
    				{
    					if ($counter == $page)
    						$pagination.= "<li class='page-item active'><a class='page-link'>$counter</a></li>";
    					else
    						$pagination.= "<li class='page-item'><a href='{$url}page=$_GET[page]&nav=$counter' class='page-link'>$counter</a></li>";					
    				}
    			}
    		}
    		
    		if ($page < $counter - 1){ 
    			$pagination.= "<li class='page-item'><a href='{$url}page=$_GET[page]&nav=$next' class='page-link'>Next</a></li>";
                $pagination.= "<li class='page-item'><a href='{$url}page=$_GET[page]&nav=$lastpage' class='page-link'>Last</a></li>";
    		}else{
                    $pagination.= "<li class='page-item disabled'><a class='page-link'>Next</a></li>";
                    $pagination.= "<li class='page-item disabled'><a class='page-link'>Last</a></li>";
                }
    		$pagination.= "</ul>\n";		
    	}
    
    
        return $pagination;
    }
	
   function pagination2($statement, $param1, $per_page = 10,$page = 1, $url = '?'){
        $database = DB::getInstance();
    	$query = "SELECT 1 FROM {$statement}";
    	$rows = $database->num_rows( $query );
    	$total = $rows;
        $adjacents = "2"; 

    	$page = ($page == 0 ? 1 : $page);  
    	$start = ($page - 1) * $per_page;								
		
    	$prev = $page - 1;							
    	$next = $page + 1;
        $lastpage = ceil($total/$per_page);
    	$lpm1 = $lastpage - 1;
    	
    	$pagination = "";
    	if($lastpage > 1)
    	{	
    		$pagination .= "<ul class='pagination'>";
                $pagination .= "<li class='page-item disabled'><a class='page-link'>Page $page of $lastpage</a></li>";
    		if ($lastpage < 7 + ($adjacents * 2))
    		{	
    			for ($counter = 1; $counter <= $lastpage; $counter++)
    			{
    				if ($counter == $page)
    					$pagination.= "<li class='page-item active'><a class='page-link'>$counter</a></li>";
    				else
    					$pagination.= "<li class='page-item'><a href='{$url}page=$_GET[page]&seq=$param1&nav=$counter' class='page-link'>$counter</a></li>";
    			}
    		}
    		elseif($lastpage > 5 + ($adjacents * 2))
    		{
    			if($page < 1 + ($adjacents * 2))		
    			{
    				for ($counter = 1; $counter < 4 + ($adjacents * 2); $counter++)
    				{
    					if ($counter == $page)
    						$pagination.= "<li class='page-item active'><a class='page-link'>$counter</a></li>";
    					else
    						$pagination.= "<li class='page-item'><a href='{$url}page=$_GET[page]&seq=$param1&nav=$counter' class='page-link'>$counter</a></li>";					
    				}
    				$pagination.= "<li class='page-item dot'>...</li>";
    				$pagination.= "<li class='page-item'><a href='{$url}page=$_GET[page]&seq=$param1&nav=$lpm1' class='page-link'>$lpm1</a></li>";
    				$pagination.= "<li class='page-item'><a href='{$url}page=$_GET[page]&seq=$param1&nav=$lastpage' class='page-link'>$lastpage</a></li>";		
    			}
    			elseif($lastpage - ($adjacents * 2) > $page && $page > ($adjacents * 2))
    			{
    				$pagination.= "<li class='page-item'><a href='{$url}page=$_GET[page]&seq=$param1&nav=1' class='page-link'>1</a></li>";
    				$pagination.= "<li class='page-item'><a href='{$url}page=$_GET[page]&seq=$param1&nav=2' class='page-link'>2</a></li>";
    				$pagination.= "<li class='page-item dot'>...</li>";
    				for ($counter = $page - $adjacents; $counter <= $page + $adjacents; $counter++)
    				{
    					if ($counter == $page)
    						$pagination.= "<li class='page-item active'><a class='page-link'>$counter</a></li>";
    					else
    						$pagination.= "<li class='page-item'><a href='{$url}page=$_GET[page]&seq=$param1&nav=$counter' class='page-link'>$counter</a></li>";					
    				}
    				$pagination.= "<li class='page-item dot'>..</li>";
    				$pagination.= "<li class='page-item'><a href='{$url}page=$_GET[page]&seq=$param1&nav=$lpm1' class='page-link'>$lpm1</a></li>";
    				$pagination.= "<li class='page-item'><a href='{$url}page=$_GET[page]&seq=$param1&nav=$lastpage' class='page-link'>$lastpage</a></li>";		
    			}
    			else
    			{
    				$pagination.= "<li class='page-item'><a href='{$url}page=$_GET[page]&seq=$param1&nav=1' class='page-link'>1</a></li>";
    				$pagination.= "<li class='page-item'><a href='{$url}page=$_GET[page]&seq=$param1&nav=2' class='page-link'>2</a></li>";
    				$pagination.= "<li class='page-item dot'>..</li>";
    				for ($counter = $lastpage - (2 + ($adjacents * 2)); $counter <= $lastpage; $counter++)
    				{
    					if ($counter == $page)
    						$pagination.= "<li class='page-item active'><a class='page-link'>$counter</a></li>";
    					else
    						$pagination.= "<li class='page-item'><a href='{$url}page=$_GET[page]&seq=$param1&nav=$counter' class='page-link'>$counter</a></li>";					
    				}
    			}
    		}
    		
    		if ($page < $counter - 1){ 
                    $pagination.= "<li class='page-item'><a href='{$url}page=$_GET[page]&seq=$param1&nav=$next' class='page-link'>Next</a></li>";
                    $pagination.= "<li class='page-item'><a href='{$url}page=$_GET[page]&seq=$param1&nav=$lastpage' class='page-link'>Last</a></li>";
    		}else{
                    $pagination.= "<li class='page-item disabled'><a class='page-link'>Next</a></li>";
                    $pagination.= "<li class='page-item disabled'><a class='page-link'>Last</a></li>";
                }
    		$pagination.= "</ul>\n";		
    	}
    
    
        return $pagination;
    }	
?>