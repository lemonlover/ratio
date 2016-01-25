 $(function(){
    var rating  = $(".rate-cnt"); 
    var score = $(rating).attr("data-saved-rate"); 
    var num_of_stars = $(rating).attr("data-max-rate"); 
    var init_star_rating = function(rating,num_of_stars,score){
        
        var counter = 0; 
        while (counter < num_of_stars){
            $(rating).append($("<li>").attr({title:counter+1}));
            counter++;
        }

        var setStartScore = function(rating,score) {
            $(rating).find("li").each(function(i,e){
                if(i < score)
                    $(e).addClass("cheked"); 
            }); 
        }; 
        setStartScore(rating,score);
    }
    init_star_rating(rating,num_of_stars,score);
});

$(document).on("click",".rate-cnt li", function(){
    var current = $(this).index();

    if(!$(this).hasClass("cheked")) 
        $(this).addClass("cheked");
    
    $(this).siblings().each(function(i,e){
        if( $(e).index() < current) 
             $(e).addClass("cheked");
        else $(e).removeClass("cheked");
    });
});


$(document).on("click",".btn", function(e){
    e.preventDefault(); 
    if(!$(this).hasClass("active"))
         $(this).addClass("active");
    else $(this).removeClass("active");
});