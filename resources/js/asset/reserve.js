$('#stylist').on('change', function(){

  $('#menu').html('');



  var option;

  option = '<option value=1>カット</option>';
  $('#menu').append(option);

  option = '<option value=1>カラー</option>';
  $('#menu').append(option);
  
  option = '<option value=1>パーマ</option>';
  $('#menu').append(option);
  
  option = '<option value=1>カット・カラー</option>';
  $('#menu').append(option);
  
  option = '<option value=1>カット・パーマ</option>';
  $('#menu').append(option);



});