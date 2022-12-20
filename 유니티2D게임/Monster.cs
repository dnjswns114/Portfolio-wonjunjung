using System.Collections;
using System.Collections.Generic;
using UnityEngine;

public class Monster : MonoBehaviour
{
	   Rigidbody2D rigid;
	 public int randomSpeed;
	 void Awake()
	 {
	 rigid = GetComponent<Rigidbody2D>();
	 Invoke("Self_Move", 1); // 1�ʵ� Self_Move �Լ� ȣ���� 1ȸ�� ����
	 }
	 void FixedUpdate()
	 { 
	 rigid.velocity = new Vector2(randomSpeed, rigid.velocity.y);
	 
	 Vector2 front = new Vector2(rigid.position.x + randomSpeed * 0.4f, rigid.position.y);
	 bool isNotFall = Physics2D.Raycast(front, Vector2.down, 1);

	 if (!isNotFall)
	 randomSpeed = -1*randomSpeed; // monster Fall �̸� ���� ����� �ݴ��


	 }
	 void Self_Move() // Recursive Function ����Լ�
	 {
	 randomSpeed = Random.Range(-2, 3);
	 Invoke("Self_Move", 1); // 1�� ���� �ڽ��� �Լ� Self_Move �Լ� ȣ��, ����Լ�����
	 }

}
