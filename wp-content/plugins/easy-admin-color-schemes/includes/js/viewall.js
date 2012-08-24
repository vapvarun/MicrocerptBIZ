/*
Easy Admin Color Schemes <http://JamesDimick.com/easy-admin-color-schemes>
Created by James Dimick <http://JamesDimick.com>

  Copyright (C) 2011 James Dimick <mail@jamesdimick.com> - JamesDimick.com

  This program is free software: you can redistribute it and/or modify
  it under the terms of the GNU General Public License as published by
  the Free Software Foundation, either version 3 of the License, or
  (at your option) any later version.

  This program is distributed in the hope that it will be useful,
  but WITHOUT ANY WARRANTY; without even the implied warranty of
  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
  GNU General Public License for more details.

  You should have received a copy of the GNU General Public License
  along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/

jQuery(function($){
  $('tbody#the-list td.name div.row-actions.can-preview').each(function(){
    $(this).prepend('<span class="view"><a href="' + eacsL10n.adminBaseUrl + 'index.php?TB_iframe=true" title="' + eacsL10n.previewSchemeTitle + '" class="thickbox">' + eacsL10n.previewSchemeText + '</a>' + ($(this).not(':empty').length?' | ':'') + '</span>')
      .find('a.thickbox')
        .click(waitThenCallback);
  });

  $('tbody#the-list td.name div.row-actions span.export a')
    .addClass('thickbox')
    .click(waitThenCallback)
    .attr('href', function(i,val){return val + '&TB_iframe=true&height=400&width=650'});

  function thickDims() {
    var tbWindow = $('#TB_window'), h = $(window).height() - 60, w = $(window).width() - 90;

    if(tbWindow.size()) {
      tbWindow.width(w).height(h);
      $('#TB_iframeContent').width(w).height(h - 27);
      tbWindow.css({ marginLeft: '-' + parseInt((w/2), 10) + 'px' });
      if(typeof document.body.style.maxWidth != 'undefined') tbWindow.css({ top: '30px', marginTop: 0 });
    }
  }

  function waitThenCallback(e) {
    if($('#TB_iframeContent').length < 1) {
      setTimeout(function(){waitThenCallback(e)}, 100);
      return;
    } else {
    $('#TB_iframeContent').load({passedData:e}, ($(e.currentTarget).parent().attr('class')=='view'?updatePreviewFrame:updateExportFrame));
    }
  }

  function updatePreviewFrame(e) {
    var $tr = $(e.data.passedData.currentTarget).closest('tr'), $fm = $('#TB_iframeContent');

    $(window)
      .unbind('resize', thickDims)
      .resize(thickDims);

    $('#TB_ajaxWindowTitle')
      .html(eacsL10n.previewQuote + $tr.find('a.row-title').text() + eacsL10n.endQuote);

    $fm.width('100%');
    thickDims();

    $('head #colors-css', $fm.contents())
      .attr('href', eacsL10n.adminBaseUrl + 'post.php?action=eacs-css&post=' + $tr.attr('id').replace('post-', ''));
  }

  function updateExportFrame(e) {
    var $html = $('html', $('#TB_iframeContent').contents());

    $(window).unbind('resize', thickDims);

    $('#TB_ajaxWindowTitle')
      .html(eacsL10n.exportQuote + $(e.data.passedData.currentTarget).closest('tr').find('a.row-title').text() + eacsL10n.endQuote);

    $('div.wrap', $html).appendTo($('body', $html));

    $('body > *:not(div.wrap)', $html).remove();

    $('#icon-edit', $html).unwrap().remove();

    $('#eacs-author-support-link', $html).remove();

    $('body', $html)
      .addClass('wrap')
      .css({ padding: '1em 1em 1.5em', height: 'auto', minWidth: 0, margin: 0 });

    $('body > h2:first-of-type', $html)
      .css({ fontFamily: 'Georgia,"Times New Roman",Times,serif', fontWeight: 'normal', fontSize: '1.6em', paddingTop: 0 })
      .text('Select the export method');

    $('body > p:first-of-type', $html).css({ margin: '0 0 1em' });

    $('body .submit', $html).css({ padding: 0, margin: '1em 0 0' });

    $('#TB_iframeContent').height($('body', $html).outerHeight());
  }
});