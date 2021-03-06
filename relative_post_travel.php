<?php
/*==========================Create Relative Products Link ============================*/	
		
	add_action( 'admin_menu', 'register_my_custom_menu_page' );

	function register_my_custom_menu_page(){

	  add_menu_page( 'Sản phẩm liên quan', 'Sản phẩm liên quan ', 'edit_others_posts', 'link-san-pham-lien-quan', 'my_custom_menu_page' ); 

	}

	function my_custom_menu_page(){
	$input_value = get_option('other_news_number');
	if(!isset($input_value)){
	 add_option( 'other_news_number', '4', '', 'yes' );
	}
	$input_value = get_option('other_news_number');
	
	 if(isset($_POST['submit']) and $_POST['submit']=="update"){
		update_option( 'other_news_number', stripcslashes($_POST['input_value']) );
	}
	$input_value = get_option('other_news_number');
	

	?>
	<h3>Cập nhập số sản phẩm liên quan</h3>
	<form method="post" action="">

	<input  name="input_value" type="text" value="<?php echo $input_value; ?>"/>
		<p class="submit">
				<input type="submit" class="button-primary" name="submit" value="update" />
		</p>
	</form>
	<?php
	
	}

	add_shortcode('sanphamlienquan',function($content){ // hoac co the dung add_filter()
		global $wpdb,$blog_id;
		
		$id = get_the_id();
		
		$news_numbers = (int)get_option('other_news_number');
		
		if($blog_id){$news_numbers =5;}
		if(!is_singular('post')){
			return $content;
		}
		$terms = get_the_terms($id,'category');
		//print_r($terms);
		$cats = array();
		foreach($terms as $term){
			$cats[] = $term->term_id;
		}
		
		$loop = new WP_Query(
			array(
				'posts_per_page'=>$news_numbers,
				'category__in'=>$cats,
				'orderby'=>'rand',
				'post__not_in'=>array($id)
				)
		);
		//print_r($loop);
		if($loop->have_posts()) {
			$content.='<div style="margin:40px 0 20px 0 ;border-bottom: 1px dashed #FF7519;"></div>
				<h4>Các sản phẩm khác</h4></br>
				<p>
			';
			while($loop->have_posts()){
				$loop->the_post();
				$custom = get_post_custom($post->ID);
				$path_image = unserialize($custom["thumbs"][0]);
				if(count($path_image)>0){$path_image_thumbs_first = $path_image[0];}else{$path_image_thumbs_first ='';}
				
				$content.='
				<div style="clear:both; padding-bottom:5px; ">
				<img src="'.@$path_image_thumbs_first.'" width="80" height="60"  align="left"  hspace="5" >
				<strong>
					<a href="'. get_permalink().'">'.get_the_title().'</a>	</strong><br/>
					Thời gian: '.$custom["tourtime"][0].'<br/>
					Giá: <font class="price2">'.number_format($custom["price"][0]) .' Đ </font>
				</div><br/>
				';
			}
			$content.='</p><br/>';
			wp_reset_query();
			
		}
		return $content;
		
	});
	
	
	//-----------tin tuc lien quan

add_shortcode('tintuclienquan',function($content){ // hoac co the dung add_filter()
		global $wpdb;
		$id = get_the_id();
		
		$news_numbers = (int)get_option('other_news_number');

		if(!is_singular('portfolio')){
			return $content;
		}

		 $loop = new WP_Query(
			array(
				'post_type' => 'portfolio',
				'posts_per_page'=>$news_numbers,
				'orderby'=>'rand',
				'post__not_in'=>array($id)
				)
		);
		if($loop->have_posts()) {
			$thongtinlienquan ='<h2 >Tin tức khác</h2><div style="padding:15px 0 20px 0">';
			while($loop->have_posts()){
				
				$loop->the_post();
				
				$thongtinlienquan.='
				<div> - &nbsp; <a href="'. get_permalink().'">'. get_the_title() .'</a></div>' ;
			}
			$thongtinlienquan.='</div>';
			
			wp_reset_query();
			
		}
		return $thongtinlienquan;
		
	});
