using System.Collections;
using System.Collections.Generic;
using UnityEngine;
using UnityEngine.UI;
using TMPro;
public class Ball : MonoBehaviour
{
    //전역변수로 선언
    Rigidbody rigid;
    Collider coll;
    public float mass;
    public float torque;
    public float power;
    public float dynFriction;
    
    // 인풋필드와 텍스트TMP 변수 선언
    public TMP_InputField input_Mass,input_Firction,input_Power,input_Rotation;
    public TextMeshProUGUI output_Mass,output_friction,output_power,output_rotation;

   
    // Start is called before the first frame update
    void Start()
    {
        rigid = GetComponent<Rigidbody>();
        coll = GetComponent<Collider>();
  


    }

    // Update is called once per frame
    void Update()
    {
       
        

    }
    //FixedUpdate에 좌우 이동을 위한 코드 선언 transform.translate를 이용했습니다.
    void FixedUpdate()     

    {   float h = Input.GetAxis("Horizontal");
       
        h = h * Time.deltaTime;

        transform.Translate(Vector3.right * h);

 
 
    } 
    // Shoot 함수를 버튼에 연결 시킨 뒤 power 와 torque 파라미터를 넘겨받아 적용할 수 있도록 했습니다.
    public void Shoot()
    
    {

           Vector3 vec = new Vector3(0, 0, 1);
           rigid.AddForce(vec * power, ForceMode.Impulse);
           rigid.AddTorque(Vector3.back * torque); // 양수 값의 경우 오른쪽으로 회전, 음수일 경우 왼쪽으로 회전
     
        
    }
    // 각 파라미터를 인풋필드를 통해서 입력 받고 파라미터 적용하기 버튼을 눌러서 적용시키도록 구현했습니다.
    public void MassButton() {
                if (float.TryParse(input_Mass.text, out mass))
                    rigid.mass = mass;
                else             
                    rigid.mass = 1.0f; 
 
                output_Mass.text = mass.ToString();
        }

    public void FrictionButton() {
                if (float.TryParse(input_Firction.text, out dynFriction))
                    coll.material.dynamicFriction = dynFriction;
                else             
                    coll.material.dynamicFriction = 0.5f; 
 
                output_friction.text = coll.material.dynamicFriction.ToString(); 
        }

    public void PowerButton() {
                if (float.TryParse(input_Power.text, out power))
                    this.power=power;
                else             
                    this.power = 1.0f; 
 
                output_power.text = power.ToString();
        }

    public void RotationButton() {
                if (float.TryParse(input_Rotation.text, out torque))
                    this.torque=torque;
                else             
                    this.torque = 0;
 
                output_rotation.text = torque.ToString();     }

}  