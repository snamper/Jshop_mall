<template>
	<view style="width: 100%;height: 300upx;background: #FFFFFF;position: absolute;left:0;bottom: 0;">
		<view class="share-pop">
			<view class="share-item" @click="createPoster()">
				<image src="/static/image/poster.png" mode=""></image>
				<view class="">生成海报</view>
			</view>
		</view>
		<view class="button-bottom">
			<button class="btn btn-w btn-square" @click="close()">关闭</button>
		</view>
	</view>
</template>

<script>
import { apiBaseUrl } from '@/config/config.js'
// #ifdef MP-TOUTIAO
import {ttPlatform} from '@/config/config.js'
// #endif
export default {
	props: {
		// 商品id
		goodsId: {
			type: Number,
			default: 0
		},
		// 分享的图片
		shareImg: {
			type: String,
			default: ''
		},
		// 分享标题
		shareTitle: {
			type: String,
			default: ''
		},
		// 分享内容
		shareContent: {
			type: String,
			default: ''
		},
		// 分享链接
		shareHref: {
			type: String,
			default: ''
		},
		//分享类型
		shareType:{
			type:Number,
			default:1
		},
		//拼团id
		groupId:{
			type:Number,
			default:0
		},
		//拼团的团队id
		teamId:{
			type:Number,
			default:0
		}
	},
	data () {
		return {
			providerList: [] // 分享通道 包含生成海报
		}
	},
	mounted () {
		
	},
	methods: {
		// 关闭弹出层
		close () {
			this.$emit('close')
		},
		// 生成海报
		createPoster () {
			let data = {
				id: this.goodsId,
				type: this.shareType,
				group_id :this.groupId,
				team_id :this.teamId,
			}
			
			let pages = getCurrentPages()
			let page = pages[pages.length - 1]
			
			data.source = 4;
			data.return_url = 'pages/share/jump';//page.route;
			data.tt_platform = ttPlatform;
			
			let userToken = this.$db.get('userToken')
			if (userToken) {
				data.token = userToken
			}
			
			this.$api.createPoster(data, res => {
				if (res.status) {
					this.close()
					this.$common.navigateTo('/pages/share?poster=' + res.data)
				} else {
					this.$common.errorToShow(res.msg)
				}
			})
		}
	}
}	
</script>

<style>
.share-pop{
	height: 300upx;
	width: 100%;
	display: flex;
}
.share-item{
	flex: 1;
	text-align: center;
	font-size: 26upx;
	color: #333;
	padding: 20upx 0;
}
.share-item image{
	width: 80upx;
	height: 80upx;
	margin: 20upx;
}	
.share-item .btn{
	line-height: 1;
	display: block;
	font-size: 26upx;
	background-color: #fff;
}
</style>
