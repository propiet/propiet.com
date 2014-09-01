var slugString = function (input){
    return input
        .replace(/^\s\s*/, '') // Trim start
        .replace(/\s\s*$/, '') // Trim end
        .toLowerCase() // Camel case is bad
        .replace(/[^a-z0-9_\-~!\+\s]+/g, '') // Exchange invalid chars
        .replace(/[\s]+/g, '-'); // Swap whitespace for single hyphen
};

var generateUri = function(){
    var  operationSelect = $("#operationSelect>option:selected").data('slug'),
     propertySelect = $("#propertySelect>option:selected").data('slug'),
     regionSelect = $("#regionSelect>option:selected").data('slug'),
     citySelect = $("#citySelect").data('cities'),
     priceMin = $("#priceMin").val(),
     priceMax = $("#priceMax").val(),
     currency = $("#currencyType>label>input:checked").val(),
     baseUrl = $("#searchForm").data('baseurl'),
     slug="",
     operation,
     category,
     region,
     city,
     price_min,
     price_max,
//     $operation =$("#operation"),
//     $category =$("#category"),
//     $region =$("#region"),
//     $city =$("#city"),
//     $price_min =$("#price_min"),
//     $price_max =$("#price_max"),
//     $currency =$("#currency");     
    operation = operationSelect ? operationSelect : null;
    category = propertySelect ? propertySelect : null;
    region = regionSelect ? regionSelect : null;
    city = citySelect ? citySelect : null;
    price_min = priceMin ? priceMin : null;
    price_max = priceMax ? priceMax : null;
    
    if(operation){
        baseUrl+=operation;
    }
    
    if (category){
        var slugCategory = slugString(category);
        baseUrl+= operation ? '/'+slugCategory : slugCategory;
    }
    
    if (city){
        var citiesEncode = encodeURI(city);
        slug+="_barrio_"+ citiesEncode;
    }
    
    if (region){
        slug+="_ciudad_"+ region;
    }
    
    if (price_min){
        slug+="_pmin_"+ price_min;
    }
    
    if (price_max){
        slug+="_pmax_"+ price_max;
    }
    
    if (currency){
        slug+="_cr_"+ currency;
    }
    
    if(!operation && !category){
        baseUrl+="publicaciones/_ciudad_capital-federal";
    }
    
    $("#searchForm").attr('action',baseUrl+"/"+slug).submit();
};

var removeFilter = function(filter){
    var uri,
    path = location.pathname.split("/"),
    host = location.host;
    !path[0] ? path.shift():false;
    var builderUri = function(value,replace){
        switch (replace) {
            case "city":
                if(path.length === 3){
                    var encodeValue = encodeURI(value+"|");
                    path[2] = path[2].replace(encodeValue,'');
                    uri = "/"+path[0]+"/"+path[1]+"/"+path[2];
                    return uri;
                }else if(path.length === 2){
                    var encodeValue = encodeURI(value+"|");
                    path[1] = path[1].replace(encodeValue,'');
                    uri = "/"+path[0]+"/"+path[1];
                    return uri;
                }
              break;
            case "price":
                if(path.length === 3){
                    path[2] = path[2].replace(value,'');
                    uri = "/"+path[0]+"/"+path[1]+"/"+path[2];
                    return uri;
                }else if(path.length === 2){
                    path[1] = path[1].replace(value,'');
                    uri = "/"+path[0]+"/"+path[1];
                    return uri;
                }
              break;
            case "category":
                $.each(path,function(key,val){
                    if(val === value){
                        path.splice( key, 1 );
                        uri='';
                        $.each(path,function(key,val){
                            if(path.length === 1){
                                uri+="/publicaciones/"+val;
                            }else{
                                uri+="/"+val;
                            }
                        });
                        return uri;
                    }
                });
                return uri;
              break;
            case "operation":
                $.each(path,function(key,val){
                    if(val === value){
                        path.splice( key, 1 );
                        uri='';
                        $.each(path,function(key,val){
                            if(path.length === 1){
                                uri+="/publicaciones/"+val;
                            }else{
                                uri+="/"+val;
                            }
                        });
                        return uri;
                    }
                });
                return uri;
              break;
            case "currency":
                if(path.length === 3){
                    path[2] = path[2].replace(value,'');
                    uri = "/"+path[0]+"/"+path[1]+"/"+path[2];
                    return uri;
                }else if(path.length === 2){
                    path[1] = path[1].replace(value,'');
                    uri = "/"+path[0]+"/"+path[1];
                    return uri;
                }
                return uri;
              break;
            default:
              return null;
        }
    };
    
    if($(filter).data('filteregion')){
        location.href = "/publicaciones";
    }else if($(filter).data('filtercity')){
        var data = $(filter).data('filtercity'),
        newPath = builderUri(data,'city');
        location.href = newPath;
    }else if($(filter).data('filterpminmax')){
        var data = $(filter).data('filterpminmax'),
        newPath = builderUri(data,'price');
        location.href = newPath;
    }else if($(filter).data('filterpmin')){
        var data = $(filter).data('filterpmin'),
        newPath = builderUri(data,'price');
        location.href = newPath;
    }else if($(filter).data('filterpmax')){
        var data = $(filter).data('filterpmax'),
        newPath = builderUri(data,'price');
        location.href = newPath;
    }else if($(filter).data('filtercategory')){
        var data = $(filter).data('filtercategory'),
        newPath = builderUri(data,'category');
        location.href = newPath;
    }else if($(filter).data('filteroperation')){
        var data = $(filter).data('filteroperation'),
        newPath = builderUri(data,'operation');
        location.href = newPath;
    }else if($(filter).data('filtercr')){
        var data = $(filter).data('filtercr'),
        newPath = builderUri(data,'currency');
        location.href = newPath;
    }
};

var addFilter = function(filter){
    var uri,
    path = location.pathname.split("/"),
    host = location.host;
    !path[0] ? path.shift():false;
    var builderUri = function(value,replace){
        var operation = ['venta','emprendimiento','alquiler'];
        switch (replace) {
            case "category":
                if(path.length === 3){
                    uri = "/"+path[0]+"/"+value+"/"+path[2];
                }else if(path.length === 2){

                    $.each(path,function(key,val){
                        $.each(operation,function(opkey,opval){
                            if(opval===val){
                                uri = '/'+path[0]+'/'+value+'/'+path[1];
                                return uri;
                            }
                        });
                    });
                    uri = '/'+value+'/'+path[1];
                }
                return uri;
              break;
              case "operation":
                if(path.length === 3){
                    uri = "/"+value+"/"+path[1]+"/"+path[2];
                }else if(path.length === 2){
                    $.each(path,function(key,val){
                        $.each(operation,function(opkey,opval){
                            if(opval===val){
                                uri = '/'+value+'/'+path[1];
                                return uri;
                            }
                        });
                    });
                    uri = "/"+value+"/"+path[0]+'/'+path[1];
                }
                return uri;
              break;
              case "city":
               if(path.length === 3){
                    var encodeValue = encodeURI(value+"|");
                    if(path[2].match('_barrio_')){
                        path[2] = path[2].replace('_barrio_','_barrio_'+encodeValue);
                        uri = "/"+path[0]+"/"+path[1]+"/"+path[2];
                    }else{
                        path[2] = '_barrio_'+encodeValue+path[2];
                        uri = "/"+path[0]+"/"+path[1]+"/"+path[2];
                    }
                }else if(path.length === 2){
                    var encodeValue = encodeURI(value+"|");
                    if(path[1].match('_barrio_')){
                        path[1] = path[1].replace('_barrio_','_barrio_'+encodeValue);
                        uri = "/"+path[0]+"/"+path[1];
                    }else{
                        path[1] = '_barrio_'+encodeValue+path[1];;
                        uri = "/"+path[0]+"/"+path[1];
                    }
                }
                return uri;
              break;
              case "price":
               if(path.length === 3){
                    path[2] = path[2].replace(/_pmin_\d*/gi,'');
                    path[2] = path[2].replace(/_pmax_\d*/gi,'');
                    path[2]+= value["pmin"] ? '_pmin_'+value["pmin"]:'';
                    path[2]+= value["pmax"] ? '_pmax_'+value["pmax"]:'';
                    uri = "/"+path[0]+"/"+path[1]+"/"+path[2];
                }else if(path.length === 2){
                    path[1] = path[1].replace(/_pmin_\d*/gi,'');
                    path[1] = path[1].replace(/_pmax_\d*/gi,'');
                    path[1]+= value["pmin"] ? '_pmin_'+value["pmin"]:'';
                    path[1]+= value["pmax"] ? '_pmax_'+value["pmax"]:'';
                    uri = "/"+path[0]+"/"+path[1];
                }
                return uri;
              break;
            default:
              return null;
        }
        
    };
    
    if($(filter).data('category')){
        var data = $(filter).data('category'),
        newPath = builderUri(data,'category');
        location.href = newPath;
    }else if($(filter).data('operation')){
        var data = $(filter).data('operation'),
        newPath = builderUri(data,'operation');
        newPath = newPath.replace("/publicaciones","");
        location.href = newPath;
    }else if($(filter).data('city')){
        var data = $(filter).data('city'),
        newPath = builderUri(data,'city');
        location.href = newPath;
    }else if($(filter).attr('id')==='priceBtn'){
        var $pmin = $('#pmin'),
            $pmax = $('#pmax'),
            data=[];
        data["pmin"]=$pmin.val();
        data["pmax"]=$pmax.val();
        newPath = builderUri(data,'price');
        location.href = newPath;
    }
};

var saveQuery = function(){
    if (typeof localStorage !== "undefined") {
        if(localStorage.getItem('prtquery')){
            var queries = localStorage.getItem('prtquery');
            queries +=";"+location.pathname;
            localStorage.setItem('prtquery',queries);
        }else{
            var query = location.pathname;
            localStorage.setItem('prtquery',query);
        }
    } else {
        //not implemented
    }
};



$(document).ready(function(){
    $("#saveQuery").on('click',function(){
        saveQuery();
    });
    alertQueries();
    $(".alert-filter").bind('closed.bs.alert', function(){
        removeFilter(this);
    });
    $("dd > a").on('click',function(){
        addFilter(this);
    });
    $("#priceBtn").on('click',function(){
        addFilter(this);
    });
});