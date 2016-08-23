// блок отображения ошибок
var jq_error_summary_place;
var jq_alert_success;

/**
 * Для поиска
 */
function searchPage()
{
	var jq_form = $('#js_id_search_form'),
		jq_field_type_element = $('#type_element'),
		jq_field_text = $('#text'),
		jq_field_site = $('#site'),
		jq_jq_field_text_place = jq_field_text.closest('.form-group');

	// зависимость полей (отображать текстовое поле)
	jq_field_type_element.on('change', function () {
		if ( $(this).data('spec_type') == $(this).val() ) {
			jq_jq_field_text_place.show(0);
		} else {
			jq_jq_field_text_place.hide(0);
		}
	});

	// отправка данных идет через аякс
	jq_form.on('submit', function () {

		jq_error_summary_place.hide(0);
		jq_alert_success.hide(0);

		$.ajax({
			url: jq_form.attr('action'),
			type: 'post',
			data: jq_form.serialize(),
			dataType: 'json',
			success: function (response) {
				if ( response.status == 'success') {
					jq_alert_success.show(0);
				}

				if ( response.status == 'error') {
					showErrors(response.data);
				}
				if ( response.status == 'fail') {
					jq_error_summary_place.html(response.msg);
					jq_error_summary_place.show(0);
				}
			},
			error: function(jqXHR, textStatus, errorThrown) {

			}
		});

		return false;
	});
}

/**
 * Отображение ошибок
 */
function showErrors(arr)
{
	var text = '<p>Исправьте следующие ошибки:</p>';
		text += '<ul>';

		$.each(arr, function(index, value){
			text += '<li>';
			text += value;
			text += '</li>';
		});

		text += '</ul>';

	jq_error_summary_place.html(text);
	jq_error_summary_place.show(0);
}

$(function() {

	jq_error_summary_place = $('.error-summary');
	jq_alert_success = $('.alert-success');

	if ( $('#js_id_search_form').length ) {
		searchPage();
	}

	// для результатов
	$('.js_class_show_elements').on('click', function () {

		$.ajax({
			url: 'result/view',
			type: 'get',
			data: {
				id : $(this).data('id')
			},
			dataType: 'html',
			success: function (response) {
				
				$('#js_id_result_modal_body').html(response);
				$('#js_id_result_modal').modal({backdrop : 'static', keyboard : false}).modal('show');
			},
			error: function(jqXHR, textStatus, errorThrown) {

			}
		});
	});


	
});