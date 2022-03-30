<?php
 include_once("header.php");
?>
<div class="container">
	<div class="row">
<?php
include_once("left.php");
?>
		<div class="col-md-9">
			<ul class="breadcrumb">
				<li><span class="glyphicon glyphicon-home"></span> <a href="index.php"> 首页</a></li>
				<li>我的购物车</li>
			</ul>
			<div class="panel panel-default">
				<div class="panel-body">
                    <table class="table table-bordered">
                    <form name="form1" method="post" action="?">
                      <caption>我的购物车</caption>
                      <?php
					  $_SESSION['total'] = null;
					  $qk = !empty($_GET['qk']) ? trim($_GET['qk']) : '';
					  if($qk=="yes"){//清空购物车
						 $_SESSION['producelist']="";
						 $_SESSION['quatity']=""; 
					  }
					  $sessionproducelist = !empty($_SESSION['producelist']) ? trim($_SESSION['producelist']) : '';
					  if(!isset($_SESSION['producelist'])){//购物车里没有商品
					echo "<tr>";
						   echo" <td height='25' colspan='6' bgcolor='#FFFFFF' align='center'>您的购物车为空!</td>";
						   echo"</tr>";
					
					}else{
					   $arraygwc=explode("@",$_SESSION['producelist']);
					   $s=0;
					   for($i=0;$i<count($arraygwc);$i++){
						   $s+=intval($arraygwc[$i]);//循环购物车总计商品数量
					   }
					  if($s==0 ){
						   echo "<tr>";
						   echo" <td height='25' colspan='6' bgcolor='#FFFFFF' align='center'>您的购物车为空!</td>";
						   echo"</tr>";
						}
					  else{ 
					?>
                      <thead>
                        <tr bgcolor="#FFEDBF">
                          <th height="35" align="center">商品名称</th>
                          <th height="35" align="center">数量</th>
                          <th height="35" align="center">市场价</th>
                          <th height="35" align="center">会员价</th>
                          <th height="35" align="center">折扣</th>
                          <th height="35" align="center">小计</th>
                          <th height="35" align="center">操作</th>
                        </tr>
                      </thead>
                      <tbody>
                    <?php
						$total=0;
						$array=explode("@",$_SESSION['producelist']);//把购物车商品放到数组
						$arrayquatity=explode("@",$_SESSION['quatity']); //分割数组
						//修改数量
						 while(list($name,$value)=each($_POST)){ //传递过来的数量循环
							  for($i=0;$i<count($array)-1;$i++){
								if(($array[$i])==$name){
									//判断数量是否大于商品数量
								$info1 = db_get_row("select * from goods where id='".$array[$i]."'");
								if($value>$info1['amount']){
									
								echo "<script language='javascript'>alert('购买大于库存，请重新选购数量！');location.href='cart.php';</script>";
								die;
							   }
							   
							   
							   
								  $arrayquatity[$i]=$value;  
								}
							}
						}
						
						$_SESSION['quatity']=implode("@",$arrayquatity);//数组元素组合为字符串
						
						for($i=0;$i<count($array)-1;$i++){ 
						   $id=$array[$i];
						   $num=$arrayquatity[$i]; //单种商品数量
						  
						   if($id!=""){
						   $info = db_get_row("select * from goods where id=".$id);//调用商品会员价格
						   $total1=$num*$info['sprice'];//计算一种商品总价
						   $total+=$total1;//计算商品总价
						   $_SESSION["total"]=$total;//商品总价讲入session
					?>
                      	<tr>
                          <td height="35"><a href="goodshow.php?id=<?php echo $info['id'];?>&categoryid=<?php echo $info['categoryid'];?>" target="_blank"><?php echo $info["title"];?></a></td>
                          <td height="35"><div class="gw_num" style="border: 1px solid #dbdbdb;width: 110px;line-height: 26px;overflow: hidden;">
                            <em class="jian">-</em>
                            <input type="text" name="<?php echo $info['id'];?>" value="<?php echo $num;?>" class="num"/>
                            <em class="add">+</em>
                        </div></td>
                          <td height="35"><?php echo $info['mprice'];?>元</td>
                          <td height="35"><?php echo $info['sprice'];?>元</td>
                          <td height="35"><?php echo @(ceil(($info['sprice']/$info['mprice'])*100))."%";?></td>
                          <td height="35"><?php echo $info['sprice']*$num."元";?></td>
                          <td height="35"><a href="removecart.php?id=<?php echo $info['id']?>">移除</a></td>
                        </tr>
                     <?php
						  }
						 }
					 ?>
                      </tbody>
                     <tbody>
                      	<tr>
                      	  <td colspan="7" align="center">
                        <table class="table" style="margin-bottom: 0px;" height="25" border="0" align="center" cellpadding="0" cellspacing="0">
                        <tr>
                          <td align="center"><button name="submit2" type="submit" class="btn btn-default">更改商品数量</button></td>
                          <td align="center"><a href="cart2.php"><button type="button" class="btn btn-default">去收银台</button></a></td>
                          <td align="center"><a href="cart.php?qk=yes"><button type="button" class="btn btn-default">清空购物车</button></a></td>
                          <td align="center"><a href="goods.php"><button type="button" class="btn btn-default">继续购物</button></a></td>
                          <td align="center">总计：<?php echo $total;?>元</td>
                        </tr>
                      </table>
                        </td>
                       </tr>
                      </tbody>
                     <?php
						 }}
						?></form>
                    </table>
				</div>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
	$(document).ready(function(){
	//加的效果
	$(".add").click(function(){
	var n=$(this).prev().val();
	var num=parseInt(n)+1;
	if(num==0){ return;}
	$(this).prev().val(num);
	});
	//减的效果
	$(".jian").click(function(){
	var n=$(this).next().val();
	var num=parseInt(n)-1;
	if(num==0){ return}
	$(this).next().val(num);
	});
	})
</script>
<?php
	include_once("footer.php");
?>