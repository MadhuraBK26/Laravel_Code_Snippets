/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

function showRevisionHistoryModal(url){
    $.get(url, function(data) {
      $('#myModal .modal-content').html(data);
      $('#myModal').modal('show');     
    });
}