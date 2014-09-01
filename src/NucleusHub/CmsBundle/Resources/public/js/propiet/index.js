var slugString = function (input)
{
    return input
        .replace(/^\s\s*/, '') // Trim start
        .replace(/\s\s*$/, '') // Trim end
        .toLowerCase() // Camel case is bad
        .replace(/[^a-z0-9_\-~!\+\s]+/g, '') // Exchange invalid chars
        .replace(/[\s]+/g, '-'); // Swap whitespace for single hyphen
};


$('#citySelect').on('click', function (e) {
     $('#cityModal').modal('show');
});
$('#cityModalSeleccionarBtn').on('click', function (e) {
   var citiesNames = "",
   citiesSlugNames ="",
   citiesIds = "",
   $citySelectInput = $('#citySelect'),
   selectedCities = $('.checkbox-inline>label>input:checked');
   if(selectedCities.length > 0){
        $.each(selectedCities,function(key,val){
           citiesNames+=$(val).attr('data-name')+",";
           citiesSlugNames+=$(val).attr('data-name')+"|";
           citiesIds+=val.id+",";
        });
        $citySelectInput.val(citiesNames);
        $citySelectInput.attr('data-ids',citiesIds);
        $citySelectInput.attr('data-cities',citiesSlugNames);
   }
   $('#cityModal').modal('hide');
});
$('#searchBtn').on('click', function (e) {
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
        baseUrl+="publicaciones";
    }
    
    $("#searchForm").attr('action',baseUrl+"/"+slug).submit();
});
  