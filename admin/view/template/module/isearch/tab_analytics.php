<div>The table below displays the most recent search keywords, which have returned 0 results. The Count column shows how many times the search keyword has been searched.<br /><br /></div>

<table class="list">
  <thead>
  <tr>
    <td class="left">Search Term</td>
    <td class="right">Search Count</td>
    <td class="right"><a class="btn btn-primary" id="analytics_refresh"><span>Refresh Terms</span></a></td>
  </tr>
  </thead>
  <tbody id="analytics_body">
  </tbody>
</table>
<div id="analytics_pagination"></div>
<script type="text/javascript">
  var getTerms = function(page) {
    if (typeof page == 'undefined') {
      var page = 1;
    }
    
    $.ajax({
      url: 'index.php?route=module/isearch/analytics_get&token=<?php echo $token; ?>&page=' + page,
      dataType: 'json',
      beforeSend: function() {
        $('#analytics_body, #analytics_pagination').hide().empty();
      },
      success: function(data) {
        if (typeof data.analytics != 'undefined') {
          // Populate table
          for (var i in data.analytics) {
            var item = data.analytics[i];
            
            var html = '<tr>';
            html += '<td>' + item.keyword + '</td>';
            html += '<td>' + item.count + '</td>';
            html += '<td class="right"><a class="analytics_delete btn btn-danger" data-id="' + item.id + '"><span>Delete</span></a></td>';
            html += '</tr>';
            
            $('#analytics_body').append(html);
          }
          
          $('.analytics_delete').click(function(e) {
            e.preventDefault();
            
            var analytics_id = $(this).attr('data-id');
            
            $.ajax({
              url: 'index.php?route=module/isearch/analytics_delete&token=<?php echo $token; ?>&analytics_id=' + analytics_id,
              success: function() {
                getTerms();
              }
            });
          })
        }
        
        if (typeof data.pagination != 'undefined') {
          // Populate pagination
          $('#analytics_pagination').append('<div class="pagination">' + data.pagination + '</div>');
          
          $('#analytics_pagination a').click(function(e) {
            e.preventDefault();
            getTerms($(this).attr('href'));
          });
        }
        
        $('#analytics_body, #analytics_pagination').show();
      }
    });
  }
  
  $('#analytics_refresh').click(function(e) {
    e.preventDefault();
    getTerms();
  });
</script>
<style type="text/css">
  #content .box > .heading h1 {
    width: 740px;
  }

  #analytics_refresh {
    color: white;
  }
</style>