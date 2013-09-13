
	$(document).ready(function() {
	
		var j = 0;
		var delay = 1000; //millisecond delay between cycles
		function cycleThru(){
			var jmax = $("ul#logo_cycle li").length -1;
			$("ul#logo_cycle li:eq(" + j + ")")
				.animate({"opacity" : "1"} ,10)
				.animate({"opacity" : "1"}, delay)
				.animate({"opacity" : "0"}, 10, function(){
				(j == jmax) ? j=0 : j++;
				cycleThru();
			});
		};
		
		cycleThru();
	
		$("#twitter").fadeTo("fast",.7);
		$("#facebook").fadeTo("fast",.7);
		$("#rss").fadeTo("fast",.7);
		
		$("#twitter").hover(function() {
		
				$(this).stop().fadeTo("fast", 1);
				
			},function() {
			
				$(this).stop().fadeTo("fast", .7);
				
			}
		);
		
		$("#facebook").hover(function() {
		
				$(this).stop().fadeTo("fast", 1);
				
			},function() {
			
				$(this).stop().fadeTo("fast", .7);
				
			}
		);
		
		$("#rss").hover(function() {
		
				$(this).stop().fadeTo("fast", 1);
				
			},function() {
			
				$(this).stop().fadeTo("fast", .7);
				
			}
		);
		 
	 	function sidebar_links(current_page)
		{
		
			console.log(current_page);
			
			$("ul#portfolio_sidebar li").each(function() {
			
				console.log("Test");
				
			});
		
		}
	
		//Set up left and right buttons
	
		$("#left_button").stop().fadeTo("slow", 0);
		$("#right_button").stop().fadeTo("slow", 0);
	
		$("#left_button").hover(function() {
		
				$(this).stop().fadeTo("slow", 1);
				$("#right_button").stop().fadeTo("slow", 1);
				
			},function() {
			
				$(this).stop().fadeTo("slow", 0);
				$("#right_button").stop().fadeTo("slow", 0);
				
			}
		);
		
		$("#right_button").hover(function() {
		
				$(this).stop().fadeTo("slow", 1);
				$("#left_button").stop().fadeTo("slow", 1);
				
			},
			
			function() {
			
				$(this).stop().fadeTo("slow", 0);
				$("#left_button").stop().fadeTo("slow", 0);
				
			}
		);

		$("#left_button").click(function() {
		
			$("#loading").removeClass("hidden");
		
			var image = $("#main_portfolio_image_div").attr("class").split(" ");
			
			var active_class = "#portfolio_image_links li a." + image[0];
			
			$(active_class).removeClass("active");
			
			image = parseInt(image[0]);
			
			if(image <= 1)
			{
			
				$(active_class).addClass("active");
				
				$("#loading").addClass("hidden");
			
				return false;
			}
			else
			{
				
				image--
				
				image = image.toString();
				
				var element = "#portfolio_image_links li a." + image;
				
				$(element).addClass("active");
				
				var link = $(element).attr("href");
				
				var title_array = $(element).attr("title").split("/");
				
				$("#image_title").html(title_array[0]);
				$("#image_subtitle").html(title_array[1]);
				
				link = "url(" + link + ")";
				
				$("#main_portfolio_image_div").removeClass().addClass(image);
			
				$("#main_portfolio_image_div").animate({ opacity: 0, backgroundPosition: '710px 0px' }, 500).queue();
				
				$("#main_portfolio_image_div").animate({ backgroundPosition : "-1420px 0px" }, 100, function() {
				
					$("#main_portfolio_image_div").css({ "backgroundImage" : link }).queue();
					$("#main_portfolio_image_div").animate({ opacity: 1, backgroundPosition: '0px 0px' }, 500).queue();
					$("#loading").addClass("hidden");
				});
				
				$("#loading").addClass("hidden");
					
				return false;
			}
			
		});
		
		$("#right_button").click(function() {
		
			$("#loading").removeClass("hidden").queue();
		
			var image = $("#main_portfolio_image_div").attr("class").split(" ");
			
			var active_class = "#portfolio_image_links li a." + image[0];
			
			$(active_class).removeClass("active");
			
			image = parseInt(image[0]);
			
			var number_of_links = $("#portfolio_image_links li").size();
			
			if(image >= number_of_links)
			{
				
				$(active_class).addClass("active");
				
				$("#loading").addClass("hidden");
				
				return false;
			}
			else
			{
				
				image++
				
				image = image.toString();
				
				var element = "#portfolio_image_links li a." + image;
				
				$(element).addClass("active");
				
				var link = $(element).attr("href");
				
				var title_array = $(element).attr("title").split("/");
				
				$("#image_title").html(title_array[0]);
				$("#image_subtitle").html(title_array[1]);
				
				link = "url(" + link + ")";
				
				$("#main_portfolio_image_div").removeClass().addClass(image);
				
				$("#main_portfolio_image_div").animate({ opacity: 0, backgroundPosition: '-710px 0px' }, 500).queue();
				
				$("#main_portfolio_image_div").animate({ backgroundPosition : "1420px 0px" }, 100, function() {
				
					$("#main_portfolio_image_div").css({ "backgroundImage" : link }).queue();
					$("#main_portfolio_image_div").animate({ opacity: 1, backgroundPosition: '0px 0px' }, 500);
					$("#loading").addClass("hidden");
				});
							
				return false;
			}
			
		});
		
		//animate navigation
		
		$("ul#navigation li#portfolio a").css({ backgroundPosition: "-8px 0px" });
		$("ul#navigation li#portfolio a").hover(function(){
		
			$(this).stop().animate({ backgroundPosition: '-8px 21px' }, 200);
		
		},function(){
		
			$(this).stop().animate({ backgroundPosition: '-8px 0px' }, 200);
		
		});
		
		$("ul#navigation li#about a").css({ backgroundPosition: '-104px 0px' });
		$("ul#navigation li#about a").hover(function(){
		
			$(this).stop().animate({ backgroundPosition: '-104px 21px' }, 200);
		
		},function(){
		
			$(this).stop().animate({ backgroundPosition: '-104px 0px' }, 200);
		
		});
		
		$("ul#navigation li#shop a").css({ backgroundPosition: '-179px 0px' });
		$("ul#navigation li#shop a").hover(function(){
		
			$(this).stop().animate({ backgroundPosition: '-179px 21px' }, 200);
		
		},function(){
		
			$(this).stop().animate({ backgroundPosition: '-179px 0px' }, 200);
		
		});
		
		$("ul#navigation li#blog a").css({ backgroundPosition: '-250px 0px' });
		$("ul#navigation li#blog a").hover(function(){
		
			$(this).stop().animate({ backgroundPosition: '-250px 21px' }, 200);
		
		},function(){
		
			$(this).stop().animate({ backgroundPosition: '-250px 0px' }, 200);
		
		});
		
		$("ul#navigation li#contact a").css({ backgroundPosition: '-311px 0px' });
		$("ul#navigation li#contact a").hover(function(){
		
			$(this).stop().animate({ backgroundPosition: '-311px 21px' }, 200);
		
		},function(){
		
			$(this).stop().animate({ backgroundPosition: '-311px 0px' }, 200);
		
		});
		
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		
		$("#portfolio_image_links li a").click(function() {
		
			$("#loading").removeClass("hidden");
			
			var image = $("#main_portfolio_image_div").attr("class").split(" ");
			
			var active_class = "#portfolio_image_links li a." + image[0];
			
			$(active_class).removeClass("active");
			
			image = parseInt(image[0]);
		
			$("#main_portfolio_image_div").css( "backgroundPosition", "0px 0px");
			
			var link = "url(" + $(this).attr("href") + ")";
			var this_class = parseInt($(this).attr("class"));
			var current_image_class = image;
			
			if (this_class < current_image_class)
			{
			
				var title_array = $(this).attr("title").split("/");
				
				$("#image_title").html(title_array[0]);
				$("#image_subtitle").html(title_array[1]);
				
				$("#main_portfolio_image_div").removeClass().addClass($(this).attr("class"));
				
				$(this).addClass("active");
				
				$("#main_portfolio_image_div").animate({ opacity: 0, backgroundPosition: '710px 0px' }, 500).queue();
				
					$("#main_portfolio_image_div").animate({ backgroundPosition : "-1420px 0px" }, 100, function() {
						$("#main_portfolio_image_div").css({ "backgroundImage" : link }).queue();
						$("#main_portfolio_image_div").animate({ opacity: 1, backgroundPosition: '0px 0px' }, 500).queue();
						$("#loading").addClass("hidden");
					});
					
				return false;
				
			}
			else if (this_class > current_image_class)
			{
			
				var title_array = $(this).attr("title").split("/");
				
				$("#image_title").html(title_array[0]);
				$("#image_subtitle").html(title_array[1]);
				
				$("#main_portfolio_image_div").removeClass().addClass($(this).attr("class"));
				
				$(this).addClass("active");
				
				$("#main_portfolio_image_div").animate({ opacity: 0, backgroundPosition: '-710px 0px' }, 500).queue();
				
					$("#main_portfolio_image_div").animate({ backgroundPosition : "1420px 0px" }, 100, function() {
						$("#main_portfolio_image_div").css({ "backgroundImage" : link }).queue();
						$("#main_portfolio_image_div").animate({ opacity: 1, backgroundPosition: '0px 0px' }, 500);
						$("#loading").addClass("hidden");
					});
								
				return false;
			}
			else
			{
				
				$(active_class).addClass("active");	
						
				$("#loading").addClass("hidden");
			
				return false;
			}	
			
		});
				
	});
	
	//pre-load images
	$(window).bind("load", function()
	{ 
	
		var counter = 1;
		var image_counter = new Array();
	
		$("#portfolio_image_links li a").each(function() {
		
			var image_source = $(this).attr("href");
			image_counter[counter] = "<img src=\"" + image_source + "\" alt=\"Circuit 26 Portfolio\" />";
			counter++;
			
		
		});
	
	});