(function($) {
    var tmp = new Array;
    $.fn.addminus = function(content,max) { 
        var addele,
            minuele,
            that = $(this),
            orig = that.find("input").eq(0),
            speed = 300,
            orignum;
        if(!that.find(".addbt").length||!that.find(".minusbt").length){
        addele = $("<input>").attr({
            type: 'button',
            value: '+',
            class: "addbt",
            title:"增加"
        });
        minuele = $("<input>").attr({
            type: 'button',
            value: '－',
            class: "minusbt",
            style: "display:none",
            title:"移除"
        });
        that.append(addele, minuele);/*创建*/
 }   
        that.find(".addbt").unbind('click').bind('click',function(){
            orignum = (that.children('input').length) - 3;
             if (orignum >= max ) {
                alert(content);
                return false;
                }
             var _this = this;
             add(_this,orignum);
       });
        that.find("input.minusbt").unbind('click').bind('click',function() {
            orignum = that.children('input').length - 3
            if (orignum == 0)return;
            var _this = this;
            remov(_this,orignum);
        });
        /*增加*/
        function add(_this,num) {
           var name = orig.attr("name");
            var id = orig.attr("id");
            var title = that.children('span').text();
            var ne = $("<input>").prop({
                type: "text",
                name: name + num,
                id: id + num,
                style: "display:none",
                placeholder:"输入一个"+title+"号"
            });
            orig.after(ne);
            tmp.push(ne);        
            ne.slideDown('speed', function() {
                $(ne).show("slow");
            });
            if (num==0)that.find(".minusbt").show("fast");
           }
        /*减少*/
        function remov(_this,num) {          
            var _id = orig.attr("id")+(num-1);
            $('#'+_id).slideUp("speed", function() {
                $('#'+_id).remove();
                tmp.shift($('#'+_id));
            });
            if (num == 1) $(_this).hide("fast");            
        }
        return this;
    }

$.fn.Shake = function (shakenum , shakeDistance) {
    this.each(function () {
        var jSelf = $(this);
        jSelf.css({ position: 'relative' });
        for (var x = 1; x <= shakenum; x++) {
            jSelf.animate({ left: (-shakeDistance) }, 50)
                .animate({ left: shakeDistance }, 50)
                    .animate({ left: 0 }, 50);
            }
        });
        return this;
    }

    $.removeAllAdd = function() {
        for (var i = 0; i < tmp.length; i++) {
            tmp[i].remove();
        }
        $(".minusbt").hide();
    }
})(jQuery);
