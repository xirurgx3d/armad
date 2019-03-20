/**
 * Фильтрация автомобилей по марке и публикации
 */
$(document).ready(function(){
    $(".b-filter__zone").click(function(){
        var mark = {id:$(this).attr("id")};
        $.post("filter/filter_car",mark,function(response,status){
            if(status == 'success'){
                var data = eval("("+response+")");
                if(data.error){
                    $("#cars").empty().append(data.error);
                }
                else{
                    $("#cars").empty();
                    for(var i=0; i<data.length; i++){
                        $("#carTmpl").tmpl(data[i]).appendTo("#cars").animate({opacity:"1"},"slow");
                    }
                }
            }
        });
		var parentTag = $(this).parent();
		parentTag.find(".b-filter__zone").removeClass("checked");
		$(this).toggleClass("checked");
    });

/**
 * Фильтрация заявок на странице dashboard относительно типа
 * авто (на оценку, с пробегом, на новый автомобиль)
 */
    $(".filterdem").click(function(){
        var typecars = {id:$(this).attr("id")};
        $.post("filter/filter_demand",typecars,function(response,status){
            if(status == "success"){
                var data = eval("("+response+")");
                if(data.error){
                    $("#demands").empty().append().text(data.error);
                }
                else{
                    $("#demands").empty();
                    for(var i=0; i<data.length; i++){
                        $("#demandTmpl").tmpl(data[i]).appendTo("#demands");
                    }
                }
            }
        });
    });

    /**
     * Фильтранция заявок относительно статуса
     */
    $(".b-show-menu__item").click(function(){
        $(".b-show-menu__item").removeClass("b-show-menu__item_state_active");
        $(this).addClass("b-show-menu__item_state_active");
        var statusdem = {id:$(this).attr("id")};
        $.post("filter/filterSTdemand",statusdem,function(response,status){
            if(status == "success"){
                var data = eval("("+response+")");
                if(data.error){
                    $("#demands").empty().append().text(data.error);
                }
                else{
                    $("#demands").empty();
                    for(var i=0; i<data.length; i++){
                        $("#demandTmpl").tmpl(data[i]).appendTo("#demands");
                    }
                }
            }
        })
    })
});