<?php
/*==========================Create Relative Products Link and relative news============================*/	
		
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

//---------shortcode san pham lien quan
	
	add_shortcode('sanphamlienquan',function($content){ // or can use add_filter()
		global $wpdb;
		$id = get_the_id();
		
		$news_numbers = (int)get_option('other_news_number');

		if(!is_singular('post')){
			return $content;
		}
		$terms = get_the_terms($id,'category');

		$cats = array();
		foreach($terms as $term){
			$cats[] = $term->term_id;
		}
		
		$loop = new WP_Query(
			array(
				'post_type' => 'post',
				'posts_per_page'=>$news_numbers,
				'category__in'=>$cats,
				'orderby'=>'rand',
				'post__not_in'=>array($id)
				)
		);
	
		if($loop->have_posts()) {
			$sanphamlienquan='<div style="margin:20px 0 20px 0 ;border-bottom: 1px dashed #F2EEEC; "></div>
				<h2 style="color:#666">Tác phẩm có thể bạn yêu thích</h2></br>
				<p>
			';
		
			while($loop->have_posts()){
				$loop->the_post();
				$custom = get_post_custom($post->ID);								
				$user_id=$post->post_author;				
				$path_image = unserialize($custom["thumbs"][0]);
				if(count($path_image)>0){$path_image_thumbs_first = $path_image[0];}else{$path_image_thumbs_first ='';}

			 if($custom["price"][0]<>'' or $custom["price"][0]<>0){ 
				$show_price='<div>Giá: <font class="price2">'.number_format($custom["price"][0]) .' Đ </font></div>';
			}else{
				$show_price='';
}
		
				$sanphamlienquan.='
				<div  class="float_left" style="position:relative;margin-right:30px;width:180px;">
				<a href="'. get_permalink().'"><img src="'.@$path_image_thumbs_first.'" width="180"   ></a>
				<p>
					<div style="width:180px;"><b><a href="'. get_permalink().'">'. get_the_title() .'</a>	</b></div>				
					<div>Nghệ sĩ : '. get_the_author_meta("display_name", $user_id) .' </div>
					'.$show_price.'
				</p>	
				</div>' ;
			}
			$sanphamlienquan.='</p><br/>';
			wp_reset_query();
			
		}
		return $sanphamlienquan;
		
	});	
	
	
//---------shortcode san pham cung tac gia
		
	add_shortcode('sanphamcungtacgia',function($content){ // // or can use add_filter()
		global $wpdb;
		$id = get_the_id();
		$post_author_id = get_post_field( 'post_author', $post_id );
		
		$news_numbers = (int)get_option('other_news_number');

		if(!is_singular('post')){
			return $content;
		}
		$terms = get_the_terms($id,'category');
	
		$cats = array();
		foreach($terms as $term){
			$cats[] = $term->term_id;
		}
		
		$loop = new WP_Query(
			array(
				'post_type' => 'post',
				'posts_per_page'=>$news_numbers,
				'category__in'=>$cats,
				'author' => $post_author_id ,
				'orderby'=>'rand',
				'post__not_in'=>array($id)
				)
		);
	
		$i=1;
		if($loop->have_posts()) {
			$sanphamcungtacgia='<div style="margin:20px 0 20px 0 ;border-bottom: 1px dashed #F2EEEC; "></div>
				<h2 style="color:#666">Tác phẩm khác của tác giả</h2></br>
				<p>
			';
			while($loop->have_posts()){
				
			if($custom["price"][0]<>'' or $custom["price"][0]<>0){ 
				$show_price='<div>Giá: <font class="price2">'.number_format($custom["price"][0]) .' Đ </font></div>';
			}else{
				$show_price='';
}
				$loop->the_post();
				$custom = get_post_custom($post->ID);								$user_id=$post->post_author;				
				$path_image = unserialize($custom["thumbs"][0]);
				if(count($path_image)>0){$path_image_thumbs_first = $path_image[0];}else{$path_image_thumbs_first ='';}
				
				$sanphamcungtacgia.='
				<div  class="float_left" style="position:relative;margin-right:30px;">
				<a href="'. get_permalink().'"><img src="'.@$path_image_thumbs_first.'" width="180"   ></a>
				<p>
					<div style="width:180px;"><b><a href="'. get_permalink().'">'. get_the_title() .'</a>	</b></div>				
					<div>Nghệ sĩ : '. get_the_author_meta("display_name", $user_id) .' </div>
					'.$show_price.'
				</p>	
				</div>' ;
			}
			$sanphamcungtacgia.='</p><br/>';
			$i=$i+1;
			wp_reset_query();
			
		}
		return $sanphamcungtacgia;
		
	});		

	
//---------shortcode tin tuc lien quan

add_shortcode('tintuclienquan',function($content){ // // or can use add_filter()
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

//---------shortcode thong tin tac gia

add_shortcode('tieusutacgia',function($content){ // // or can use add_filter()
		global $wpdb;
		$id = get_the_id();
		
		$post_author_id = get_post_field( 'post_author', $post_id );

		$user_info = get_userdata($post_author_id);
	
		return $user_info->description;
		
	});
