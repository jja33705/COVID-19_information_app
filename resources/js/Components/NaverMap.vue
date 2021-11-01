<template>
    <div id="map" style="width: 100%; height: 400px"></div>
    <!-- <button>??</button> -->
</template>





<script>
export default {
    props: ['searchWay', 'lat', 'lng', 'searchResult', 'selectedTravelSpot'],
    data() {
        return {
            map: null,
            markers: [],
            infoWindows: [],
        };
    },
    mounted() {
        const mapOptions = { //맵 생성
            center: new naver.maps.LatLng(37.3595704, 127.105399),
            logoControl: true,
            mapDataControl: true,
            mapTypeControl: true,
            zoom: 1,
        };
        this.map = new naver.maps.Map("map", mapOptions);

        if (this.searchWay === 'near') { //자기위치 표시
            const currentLocation = new naver.maps.LatLng(this.lat, this.lng);
            this.map.setCenter(currentLocation);
            this.map.setOptions("zoom", 12);
            new naver.maps.Marker({
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
                    radius: 10,
                },
            });
        }
        
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
                    this.map.setOptions("zoom", 13);
                    this.infoWindows[i].open(this.map, this.markers[i]);
                    break;
                }
            }
        }
    }
}
</script>