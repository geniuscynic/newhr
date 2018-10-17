(function ($) {
     $.fn.promptInput = function (prompt, fontColor) { 
         var $this = $(this); //��ǰ�����ı���
         prompt = prompt ? prompt : $this.val(); //�����������ʾ����ʾ��
         fontColor = fontColor ? fontColor : '#ccc'; //��ʾ�����ɫ
 
         var $promptInput = $this.clone(); //��¡������ı�������չʾ
 
         $promptInput.addClass('prompt-input').css('color', fontColor)
             .attr('prompt', prompt).attr('type','text').removeAttr('name').removeAttr('id')
             .val(prompt); //ʵ��������չʾ���ı���
 
         $promptInput
             .focusin(function () { //��ȡ����ʱȥ����ʾ
                 $(this).css('color', '');
                 if ($(this).val() == $(this).attr('prompt')) {
                     $(this).val('');
                 }
             })
             .focusout(function () { //ʧȥ����ʱ��ʾ��ʾ
                 if ($(this).val().replace(/\s/g, '') == '') {
                     $(this).val($(this).attr('prompt')).css('color', fontColor);
                     $(this).next().val('');
                 }
             }).change(function () { //ֵ�����ı�ʱ��ͬʱΪ��ǰ�����ı���ֵ
                 $(this).next().val($(this).val());
             }); 
 
         $this.attr('type', 'hidden').val(''); //�ı䵱ǰ�����ı�������Ϊ������
         $promptInput.insertBefore($this); //ͬʱ׷�ӿ�¡�嵽ҳ��
     };
 })(jQuery);
 
 $(function () {
     $('.prompt-input').each(function (index, element) { //ҳ���������Զ���� .prompt-input �࣬����Ч��
         $(element).promptInput();
     });
 });