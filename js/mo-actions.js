
/* Este pacote é usado para definir as regras de máscaras dos campos de data, CPF, Hora, etc
* */

var GLOGAL_NOW = null;
	
// Initialize and change language to pt-BR
$('.typeDate').datepicker( $.datepicker.regional[ "pt-BR" ] );

/* CREATING DATE PICKER */
$('.typeDate').datepicker({
	inline: true,
	firstDay:1,
	showWeek:true,
	dateFormat:"dd/mm/yy"
})
	.mask("99/99/9999");

	
$(".typeTime")
	.mask("99:99");

$(".typeCPF")
	.mask("999.999.999-99");


$(".typeTelefone").mask("(99) 9999-9999?9");
	

 $(".currencyBRL").maskMoney({
     prefix: "R$ ",
     decimal: ",",
     thousands: "."
 });

	