(function( $ ) {
	'use strict';

	/**
	 * All of the code for your admin-facing JavaScript source
	 * should reside in this file.
	 *
	 * Note: It has been assumed you will write jQuery code here, so the
	 * $ function reference has been prepared for usage within the scope
	 * of this function.
	 *
	 * This enables you to define handlers, for when the DOM is ready:
	 *
	 * $(function() {
	 *
	 * });
	 *
	 * When the window is loaded:
	 *
	 * $( window ).load(function() {
	 *
	 * });
	 *
	 * ...and/or other possibilities.
	 *
	 * Ideally, it is not considered best practise to attach more than a
	 * single DOM-ready or window-load handler for a particular page.
	 * Although scripts in the WordPress core, Plugins and Themes may be
	 * practising this, we should strive to set a better example in our own work.
	 */
	$(document).ready(function(){
		let table_layout = []
		let cols = $('.wtl_option_columns')
		let fields = document.querySelectorAll('.woo_option_draggable')
		console.log ( fields )
		let columns = 0
		let mylayout = $('.wtl_table_layout').val()
		if ( mylayout ){
			let layout = mylayout.split(';')
			layout.forEach ( tcol => {
				table_layout.push ( tcol.split('|') )
			})
			console.log ( table_layout )
			var n = 0
			table_layout.forEach  ( _trow => {
				let mycol = $('<div class="wtl_col wtl_col_' + n + '" title="Drag to move">') 
				_trow.forEach ( _tcol => {
					let label = ''
					fields.forEach ( field => {
						if ( $(field).data('field') === _tcol ){
							label = $(field).data('label')
						}
					})
					if ( label ){
						let cfield = $('<span class="wtl_col_field" title="Double click to remove" data-field="' + _tcol + '">' + label + '</span>')
						$(cfield).appendTo(mycol)
					}
				})
				$(mycol).css({ 'margin': '5px' , 'width' : 100/layout.length + '%' , 'margin-top' : '0' }).appendTo(cols)
				n++
			})
			columns = layout.length
		}
		$( '.woo_options' ).sortable({
			revert: true,
			update: function( event, ui ) {
				recreateIndex();
			}
		})

		$( '.wtl_option_columns' ).sortable({
			revert: true,
			update: function( event, ui ) {
				$('.wtl_col_field').each(function(){
					console.log ( $(this).data('field') )
				})
				createTableCols()
				//recreateIndex();
			}
		})

		$('.woo_option_draggable').draggable(
			{
				//connectToSortable: ".woo_options",
				helper: 'clone',
				revert: "valid"
			}
		)


		
		function recreateIndex(event,ui){
			let order = []
			$('.woo_option_draggable').each(function(){
				order.push ( $(this).data('field') )
			})
			
			$('.woo_table_list_fields_order').val ( order.join(','))
		}

		$('.wtl_option_tab_1').fadeIn('slow')
		$('.wtl_column_add').on('click',function(e){
			e.preventDefault()
			columns++
			let col = $('<div class="wtl_col wtl_col_' + (columns-1) + '" title="Drag to move">')
			$(col).css('width' , 100/columns + '%').appendTo(cols)
			$('.wtl_col').css('width',100/columns + '%')
			$(col).droppable({
				accept: '.woo_option_draggable',
				drop: function ( event , ui ) {
					let dragged = $('<span class="wtl_col_field" title="Double click to remove" data-field="' + ui.draggable.data('field') + '">' + ui.draggable.find('label').text() + '</span>')
					$(this).append(dragged)
					createTableCols()
				}
			})
			createTableCols()
		})

		$('.wtl_col').droppable({
			accept: '.woo_option_draggable',
			drop: function ( event , ui ) {
				let dragged = $('<span class="wtl_col_field" title="Double click to remove" data-label="' + ui.draggable.find('label').text() + '" data-field="' + ui.draggable.data('field') + '">' + ui.draggable.find('label').text() + '</span>')
				$(this).append(dragged)
				createTableCols()
			}
		})

		$('.wtl_col_remove').droppable({
			accept: '.wtl_col',
			drop: function ( event , ui ){
				console.log ( ui )
				ui.draggable.remove()
				createTableCols()
			}
		})

		$('.wtl_fields_drawer').on ( 'click' , function(){
			$(this).parent().css('width', $(this).data('width'))
			if ( $(this).data('width') === '5%' ){
				$(this).data('width','20%')
				$('.woo_options').fadeOut()
			} else {
				$(this).data('width','5%')
				$('.woo_options').fadeIn()
			}
		})

		function createTableCols(){
			let cols = []
			let _cols = ''
			$('.wtl_col').each(function(){
				let els = $(this).find('.wtl_col_field')
				let a = []
				if ( els.length ){
					$(els).each ( function (){
						a.push ( $(this).data('field') )
					})
					cols.push ( a.join('|') ) 
				}
				if ( !$(this).find('.wtl_col_field') ){
					$(this).remove()
				}
			})
			_cols = cols.join(';')
			$('.wtl_table_layout').val ( _cols )
			console.log ( _cols , cols )
		}
		$(document).delegate ( '.wtl_col_field','dblclick',function(){
			$(this).remove()
			createTableCols()
		})

		$('.wtl_table_list_tabs').on ( 'click' , function(e){
			e.preventDefault()
			$('.wtl_table_list_tabs').removeClass('wtl_table_list_tabs_active')
			$(this).addClass('wtl_table_list_tabs_active')
			$('.wtl_table_list_tab').fadeOut('fast')
			console.log ( $(this).data('tab') )
			$('.tab_' + $(this).data('tab') ).fadeIn('slow')
		})

		$('.wtl_options_btn').on( 'click' , function(e) {
			e.preventDefault()
			$('.wtl_options_btn').removeClass('wtl_option_active')
			$(this).addClass('wtl_option_active')
			$('.wtl_option_tab').fadeOut('fast')
			$('.wtl_option_tab_' + $(this).data('tab')).fadeIn('slow')
		})

		$('.wtl_save_options').on('click',function(e){
			e.preventDefault()
			let btn = $(this)
			$(this).prop('disabled',true)
			$.ajax ( {
				url: $('.admin_url').val(),
				type: 'POST',
				data: $('#frmWooTableList').serialize()
			}).done( function (response) {
				if ( response.error ){
					$('.wtl_notice.notice-error').fadeIn('slow')
				} else {
					$('.wtl_notice.notice-info').fadeIn('slow')
				}
				setTimeout(function(){
					$('.wtl_notice').fadeOut('slow')
					btn.prop('disabled',false)
				},3000)
				
			})
		})
	})
})( jQuery );
