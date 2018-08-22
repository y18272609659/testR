@extends('layouts.app')

@section('title', '王国')
@section('content')
  @verbatim
    <el-row type="flex" justify="center">
      <el-col :span="12">
        <div style="text-align:center">
          <h3>施工队</h3>
          <el-progress type ="circle" :percentage="0"></el-progress>
          <el-progress type="circle" :percentage="25"></el-progress>
          <el-progress type="circle" :percentage="80" color="#8e71c7"></el-progress>
          <el-progress type="circle" :percentage="100" status="success"></el-progress>
          <el-progress type="circle" :percentage="50" status="exception"></el-progress>
        </div>
      </el-col>
    </el-row>

    <el-row type="flex" justify="center">
      <el-col :offset="3" :span="16">
        <span style="padding-left: 3px;" v-for="type in buildType" @click="toggle(type)">
          <el-tag>{{typeTrans(type)}}</el-tag>
        </span>
        <el-table
                :data="buildList"
                style="width: 100%">
          <el-table-column
                  prop="name"
                  label="名称"
                  width="120">
          </el-table-column>
          <el-table-column
                  prop="level"
                  label="级别"
                  width="80">
          </el-table-column>
          <el-table-column
                  prop="materialDesc"
                  label="耗材">
          </el-table-column>
          <el-table-column
                  prop="productDesc"
                  label="产出">
          </el-table-column>
          <el-table-column
                  prop="occupyDesc"
                  label="常驻">
          </el-table-column>
          <el-table-column
                  prop="time"
                  label="耗时(秒)"
                  width="100">
          </el-table-column>
          <el-table-column
                  prop="number"
                  label="拥有"
                  width="100">
          </el-table-column>
          <el-table-column
                  fixed="right"
                  label="操作"
                  width="100">
            <template slot-scope="scope">
              <el-button @click="handleClick(scope.row)" type="text" size="small">建筑</el-button>
              <el-button type="text" size="small">拆除</el-button>
            </template>
          </el-table-column>
        </el-table>
      </el-col>
    </el-row>
  @endverbatim
@endsection

@section('js')
  <script>
    let data = JSON.parse(localStorage.getItem('user'))
    data.buildingList = JSON.parse(localStorage.getItem('building'))
    data.activeHeader = 'building'
    data.activeType = 'farm'

    console.info(data.buildingList)
    let methods = {
      getBuildingList: function () {
        this.get('building/list/0.02', alert, function (request) {
        })
      },
      typeTrans: function (type) {
        typeTrans = {
          farm: '农场',
          sawmill: '伐木',
        }
        return typeTrans[type]
      },
      toggle(type) {
        console.log(this.activeType)
        this.activeType = type
      },
      handleClick(row) {
        console.log(row)
      },
    }
    let computed = {
      buildType: function () {
        let result = []
        for (let key in this.buildingList) {
          result.push(key)
        }
        return result
      },
      buildList: function () {
        console.info(this.buildingList[this.activeType])
        let result = this.buildingList[this.activeType]
        let string = ''
        let transResource = {
          'food': '食物',
          'wood': '木材',
          'stone': '石料',
          'money': '钱财',
          'people': '工人',
          'area': '土地',
        }
        for (item in result) {
          for (english in result[item].product) {
            string += transResource[english] + '：' + result[item].product[english] + ''
          }
          result[item].productDesc = string
          string = ''
          for (english in result[item].material) {
            string += transResource[english] + '：' + result[item].material[english] + ''
          }
          result[item].materialDesc = string
          string = ''
          for (english in result[item].occupy) {
            string += transResource[english] + '：' + result[item].occupy[english] + ''
          }
          result[item].occupyDesc = string
          string = ''
        }
        return result
      },
    }
  </script>
@endsection
