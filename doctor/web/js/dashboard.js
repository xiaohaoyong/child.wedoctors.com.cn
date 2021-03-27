/*
 * Author: Abdullah A Almsaeed
 * Date: 4 Jan 2014
 * Description:
 *      This is a demo file used only for the main dashboard (index.html)
 **/

$(function () {

  'use strict'

  var line = new Morris.Line({
    element          : 'line-chart',
    resize           : true,
    data             : line_data,
    xkey             : 'day',
    ykeys            : ['item1'],
    labels           : ['签约数：'],
    lineColors       : ['#efefef'],
    lineWidth        : 2,
    hideHover        : 'auto',
    gridTextColor    : '#fff',
    gridStrokeWidth  : 0.4,
    pointSize        : 4,
    pointStrokeColors: ['#efefef'],
    gridLineColor    : '#efefef',
    gridTextFamily   : 'Open Sans',
    gridTextSize     : 10
  })
  // Fix for charts under tabs
  $('.box ul.nav a').on('shown.bs.tab', function () {
    line.redraw()
  })
})
