<template>
    <div id="map" style="width: 100%; height: 500px"></div>
</template>

<script>
export default {
    props: ['searchWay', 'lat', 'lng', 'searchResult', 'selectedTravelSpot'],
    data() {
        return {
            map: null,
            markers: [],
            infoWindows: [],
            myLocationMarker: null,
        };
    },
    methods: {
        drawMyLocation(lat, lng) { //자기 위치를 그린다.
            if (this.myLocationMarker !== null) { //이미 표시돼 있는 자기 위치가 있으면 먼저 지움
                this.myLocationMarker.setMap(null);
            }
            const currentLocation = new naver.maps.LatLng(lat, lng);
            this.map.setCenter(currentLocation);
            this.map.setOptions("zoom", 11);
            this.myLocationMarker = new naver.maps.Marker({
                map: this.map,
                position: currentLocation,
                icon: {
                    path: naver.maps.SymbolPath.CIRCLE,
                    style: naver.maps.SymbolStyle.CIRCLE,
                    fillColor: "#FF0000",
                    fillOpacity: 1,
                    strokeColor: "#000000",
                    strokeStyle: "solid",
                    strokeWeight: 1,
                    radius: 8,
                },
            });
        },
        getCurrentLocationError() {
            //자기위치 가져오기(실패) 콜백
            console.log("cant get current location");
        },
        getCurrentLocationSuccess (position) { //자기위치 가져오기(성공)시 맵에 자기위치 그림
            this.drawMyLocation(position.coords.latitude, position.coords.longitude)
        },
    },
    mounted() {
        const mapOptions = { //맵 생성
            center: new naver.maps.LatLng(37.3595704, 127.105399),
            useStyleMap: true,
            zoom: 1,
        };
        this.map = new naver.maps.Map("map", mapOptions);

        if (this.searchWay === 'near') { //근처 관광지 검색했을때 자기위치 표시
            this.drawMyLocation(this.lat, this.lng);
        }

        naver.maps.Event.once(this.map, 'init_stylemap', (e) => {            
            // 내 위치 가져오기 버튼 생성
            const getLocationBtnHtml = '<button><span style="font-size: 3em;"><i class="fas fa-compass"></i></span></button>';
            const customControl = new naver.maps.CustomControl(getLocationBtnHtml, {
                position: naver.maps.Position.TOP_RIGHT
            });
            customControl.setMap(this.map);
            naver.maps.Event.addDOMListener(customControl.getElement(), 'click', () => {
                if (!navigator.geolocation) {
                    console.log("cant get location in this browser");
                } else {
                    navigator.geolocation.getCurrentPosition(
                        this.getCurrentLocationSuccess,
                        this.getCurrentLocationError
                    );
                }
            });
        })
        
        this.searchResult.map((v) => { //현재 가지고 있는 데이터들로 지도에 마커와 인포창 표시
            const spot = new naver.maps.LatLng(v.mapy, v.mapx);
            const marker = new naver.maps.Marker({
                map: this.map,
                position: spot,
            });
            this.markers.push(marker);

            var contentString = [
                '<div>',
                `   <div class="font-bold">${v.title}</div>`,
                `</div>`,
            ].join("");
            const infoWindow = new naver.maps.InfoWindow({
                content: contentString,
                borderWidth: 0,
                disableAnchor: true,
                backgroundColor: "transparent",
            });
            this.infoWindows.push(infoWindow);

            //마커 클릭시 정보창 보여줌
            naver.maps.Event.addListener(marker, "click", (e) => {
                if (infoWindow.getMap()) {
                    infoWindow.close();
                } else {
                    infoWindow.open(this.map, marker);
                }
            });
            if (this.$parent.$page.component === 'Travel/ShowTravel') { //여행지 상세정보의 지도일때는 오픈시켜놓음
                infoWindow.open(this.map, marker);
                this.map.setCenter(spot);
                this.map.setOptions("zoom", 12);
            }
        });
    },
    watch: {
        selectedTravelSpot: function (newSelectedTravelSpot) { //선택된 여행지가 바뀌면 그곳을 줌하고 정보창을 펼쳐준다.
            for (let i = 0; i < this.searchResult.length; i++) {
                if (newSelectedTravelSpot.contentid === this.searchResult[i].contentid) {
                    const spot = new naver.maps.LatLng(
                        this.searchResult[i].mapy,
                        this.searchResult[i].mapx
                    );
                    this.map.setCenter(spot);
                    this.infoWindows[i].open(this.map, this.markers[i]);
                    break;
                }
            }
        },
    },
}
</script>