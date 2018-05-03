$(function(){

	var height = $(window).height() - 75 - 60 - 77;
	$('.map,.frame').height(height);
	$('.order_manager,.peisong_manager').height(height - 1);
	
	var map = new AMap.Map('container');
	var loadDataUrl = '/index.php/Admin/System/map_data/school_id/' + school_id + '.html';
	
	loadUI();
	
	$('.reload').bind('click',function(){
		map.clearMap();
		loadUI();
	});
	
	function loadUI(){
		var UI = ['misc/MarkerList','overlay/SimpleMarker','overlay/SimpleInfoWindow'];
		AMapUI.loadUI(UI,function(MarkerList,SimpleMarker,SimpleInfoWindow){
			var defaultIconStyle = 'blue',hoverIconStyle = 'green',selectedIconStyle = 'orange';
			var markerList = new MarkerList({
				map : map,
				listContainer : 'peisong_manager',
				getPosition : function(item){
                    return [item.longitude, item.latitude];
                },
				getDataId : function(item,index){
                    return item.id;
                },
				getInfoWindow: function(data, context, recycledInfoWindow) {
                    if (recycledInfoWindow) {
                        recycledInfoWindow.setInfoTitle(data.name + '（' + data.peisong_time + '）');
                        recycledInfoWindow.setInfoBody('共送&nbsp;' + data.order_count + '&nbsp;单，已取&nbsp;' + data.yiqu + '&nbsp;单，未取&nbsp;' + data.weiqu + '单');
                        return recycledInfoWindow;
                    }
                    return new SimpleInfoWindow({
                        infoTitle: data.name + '（' + data.peisong_time + '）',
                        infoBody: '共送&nbsp;' + data.order_count + '&nbsp;单，已取&nbsp;' + data.yiqu + '&nbsp;单，未取&nbsp;' + data.weiqu + '单',
                        offset: new AMap.Pixel(0, -37)
                    });
                },
				getMarker: function(data, context, recycledMarker) {
                    var label = String.fromCharCode('A'.charCodeAt(0) + context.index);
                    if (recycledMarker) {
                        recycledMarker.setIconLabel(label);
                        return;
                    }
                    return new SimpleMarker({
                        containerClassNames: 'my-marker',
                        iconStyle: defaultIconStyle,
                        iconLabel: label
                    });
                },
				getListElement: function(data, context, recycledListElement) {
                    var label = String.fromCharCode('A'.charCodeAt(0) + context.index);
                    var innerHTML = MarkerList.utils.template('<li><p><%- data.name %>（共送：<%- data.order_count %>）</p><p>已取&nbsp;<%- data.yiqu %>，未取&nbsp;<%- data.weiqu %>，未接&nbsp;<%- data.weijie %></p><div class="clear"></div><a href="javascript:;" class="order_detail" peisong_id="<%- data.id %>">查看订单</a><div class="clear"></div></li>',{
                            data: data,
                            label: label
                        });
                    if (recycledListElement) {
                        recycledListElement.innerHTML = innerHTML;
						return recycledListElement;						
                    }
                    return innerHTML;
                },
				listElementEvents: ['click', 'mouseenter', 'mouseleave'],
                markerEvents: ['click', 'mouseover', 'mouseout'],
                selectedClassNames: 'selected',
                autoSetFitView: true
			});
			window.markerList = markerList;
			markerList.on('selectedChanged', function(event, info) {
                if (info.selected) {
                    if (info.selected.marker) {
                        info.selected.marker.setIconStyle(selectedIconStyle);
                    }
                    if (!info.sourceEventInfo.isListElementEvent) {
                        if (info.selected.listElement) {
                            scrollListElementIntoView($(info.selected.listElement));
                        }
                    }
                }
                if (info.unSelected && info.unSelected.marker) {
                    info.unSelected.marker.setIconStyle(defaultIconStyle);
                }
            });
			markerList.on('listElementMouseenter markerMouseover', function(event, record) {
                if (record && record.marker) {
                    forcusMarker(record.marker);
                    if (!this.isSelectedDataId(record.id)) {
                        record.marker.setIconStyle(hoverIconStyle);
                    }
                }
            });
            markerList.on('listElementMouseleave markerMouseout', function(event, record) {
                if (record && record.marker) {
                    if (!this.isSelectedDataId(record.id)) {
                        record.marker.setIconStyle(defaultIconStyle);
                    }
                }
            });
			
			function loadData(src,callback){
				$('.reload').text('刷新中');
				$('.order_manager ul').html('<li style="text-align:center;color:#999;">刷新中</li>');
				$('.peisong_manager ul').html('<li style="text-align:center;color:#999;">刷新中</li>');
                $.getJSON(loadDataUrl,function(data){
					$('.reload').text('刷新');
					$('.peisong_manager ul').html('');
					if(data.html == ''){
						$('.order_manager ul').html('<li style="text-align:center;color:#999;">暂无订单</li>');
					}else{
						$('.order_manager ul').html(data.html);
					}
                    markerList._dataSrc = loadDataUrl;
                    markerList.render(data.peisong);
                    if(callback) callback(null,data.peisong);
                });
            }
			
            loadData();

            function forcusMarker(marker) {
                marker.setTop(true);
                if (!(map.getBounds().contains(marker.getPosition()))) {
                    map.setCenter(marker.getPosition());
                }
            }

            function isElementInViewport(el) {
                var rect = el.getBoundingClientRect();

                return (
                    rect.top >= 0 &&
                    rect.left >= 0 &&
                    rect.bottom <= (window.innerHeight || document.documentElement.clientHeight) && /*or $(window).height() */
                    rect.right <= (window.innerWidth || document.documentElement.clientWidth) /*or $(window).width() */
                );
            }

            function scrollListElementIntoView($listEle) {
                if (!isElementInViewport($listEle.get(0))) {
                    $('#panel').scrollTop($listEle.offset().top - $listEle.parent().offset().top);
                }
                $listEle.one('webkitAnimationEnd oanimationend msAnimationEnd animationend',
				function(e) {
					$(this).removeClass('flash animated');
				}).addClass('flash animated');
            }
		});
	}	
	
});
