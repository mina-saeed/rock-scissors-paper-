<?php
include('connection.php');
$myQuery='SELECT * FROM `rules`';
$rules = mysqli_query($con,$myQuery) or die($myQuery."<br/><br/>".mysql_error());
session_start();
$_SESSION['userScore']=0;
$_SESSION['aiScore']=0;
game();
function getRules($userInput,$aiInput,$result)
{
	if($result=="ai")
	{
		canBeat($aiInput,$userInput);
	}
	else
	{
		canBeat($userInput,$aiInput);
	}
}
function canBeat($input1,$input2)
{
	global $rules ,$con;
		if(beginPrediction($input1))
		{
		if($input1=="rock")
		{
			echo "computer learned that rock beats " . $input2."<br>";
			mysqli_query($con,'INSERT INTO `rules`(`name`, `beats`) VALUES ("rock","'.$input2.'")') or die($query."<br/><br/>".mysql_error());
		}
		if($input1=="scissor")
		{
			echo "computer learned that scissor beats " . $input2."<br>";
			mysqli_query($con,'INSERT INTO `rules`(`name`, `beats`) VALUES ("scissor","'.$input2.'")') or die($query."<br/><br/>".mysql_error());
		}
		if($input1=="paper")
		{
			echo "computer learned that paper beats " . $input2."<br>";
			mysqli_query($con,'INSERT INTO `rules`(`name`, `beats`) VALUES ("paper","'.$input2.'")') or die($query."<br/><br/>".mysql_error());

		}
	}
	
}
function gameRule($user,$computer)
{
	global $computerScore,$userScore;
	if($user=="rock" && $computer=="paper")
	{
		$computerScore++;
		return "computer";
	}
	if($user=="paper" && $computer=="rock")
	{
		$userScore++;
		return "user";
	}
	if($user=="rock" && $computer=="rock")
	{
		return "tie";
	}
	if($user=="paper" && $computer=="scissor")
	{
		$computerScore++;
		return "computer";
	}
	if($user=="paper" && $computer=="paper")
	{
		return "tie";
	}
	if($user=="scissor" && $computer=="paper")
	{
		$userScore++;
		return "user";
	}
	if($user=="scissor" && $computer=="rock")
	{
		$computerScore++;
		return "computer";
	}
	if($user=="scissor" && $computer=="scissor")
	{
		return "tie";
	}
	if($user=="rock" && $computer=="scissor")
	{
		$userScore++;
		return "user";
	}
}
function computerTurn()
{
	$input = array("rock", "paper", "scissor");
	$rand_keys = array_rand($input, 1);
	return $input[$rand_keys];
}
function game()
{
	global $computerScore,$userScore,$con,$turn;
	$value=$_POST['submit'];
	$ai=computerTurn();
	$result=gameRule($value,$ai);
	mysqli_query($con,'INSERT INTO `game`(`user`) VALUES ("'.$value.'")');
	$_SESSION['turn']++;
	echo $_SESSION['turn'];
	if($result=="computer")
	{
		canBeat($ai,$value);
		echo "<br>";
		echo "computer beats you";
		echo "<br>";
	}
	if($result=="user")
	{
		echo "<br>";
		echo "you beat my AI";
		echo "<br>";
		canBeat($value,$ai);
	}
	if($result=="tie")
	{
		echo "<br>";
		echo "it's a draw";
		echo "<br>";
	}
	echo "<br>";
	echo "<br>";
	echo "<br>";
	echo "computer : ".$ai;
	echo "<br>";
	echo "<br>";
	echo "you : ".$value;
	echo "<br>";
	echo "<br>";
	echo "<br>";
	echo "<br>";
}
function beginPrediction($input1)
{
		global $rules;
		while($row=mysqli_fetch_array($rules))
		{
			if($row['name']==$input1)
			{
				return false;
			}
		}
		return true;
}
?>
<html>
	<head>
		<title>My first AI project</title>
	</head>
	<body>
		<form action="rock.php" method="post">
			<input type="submit" name="submit" value="rock" >
			<input type="submit" name="submit" value="paper">
			<input type="submit" name="submit" value="scissor">
		</form>
	</body>
</html>