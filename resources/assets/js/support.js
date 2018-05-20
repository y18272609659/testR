/**
 * 初始化用户信息
 * @param {Array} setValue [ year, month, lord, kingdom ]
 * @returns {Object}
 */
function init(setValue = ['新王', '洛德恩克斯王国', Math.floor(Math.random() * 100 + 900), 5])
{
  let userData = {}
  if (localStorage.getItem('userData') === null) {
    userData = {
      // 资源
      resource: {
        people: 10,
        food: 8,
        wood: 15,
        money: 2,
      },
      // 建筑
      building: {
        farm: [
          0,
          0,
          0,
          0,
          0,
        ],
        logging: [
          0,
          0,
          0,
          0,
          0,
        ],
      },
      // 时间
      time: {
        year: setValue[2],
        month: setValue[3],
        day: 13,
      },
      // 角色
      user: {
        lord: setValue[0],
        kingdom: setValue[1],
        avatar: 'http://wx3.sinaimg.cn/mw690/0060lm7Tly1fr0t1qi5kxj301f01fmwy.jpg',
      },
    };

    localStorage.setItem('userData', JSON.stringify(userData))
  } else {
    userData = JSON.parse(localStorage.getItem('userData'));
  }

  return userData
}

export {
  init
}