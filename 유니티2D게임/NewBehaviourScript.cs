using System.Collections;
using System.Collections.Generic;
using UnityEngine;
using UnityEngine.SceneManagement;

public class NewBehaviourScript : MonoBehaviour
{
    // Start is called before the first frame update
    public float maxSpeed;
    Rigidbody2D rigid;
    SpriteRenderer sprend;
    Animator anim;
    public float jumpPower;
    //public int score = 0;
    AudioSource item_audio;
    //public int itemCount;
    public Manager game_manager;
    


    void OnTriggerEnter2D(Collider2D collision){

        
        item_audio.Play();
        if(collision.gameObject.tag == "Item"){
            game_manager.itemCount += 1;
            item_audio.Play();
            Destroy(collision.gameObject);
            game_manager.GetItem(game_manager.itemCount);

            }

        else if(collision.gameObject.tag == "Special"){
            jumpPower = 5;
            Destroy(collision.gameObject);
            }
        else if(collision.gameObject.tag == "Finish"){
         
             
             if(game_manager.stage == 2){
                SceneManager.LoadScene("Game_0");
             }
             else if (game_manager.itemCount == game_manager.totalItemCount)
             {
                game_manager.stage++;
                SceneManager.LoadScene("Game_"+game_manager.stage);
             }
             
         }
          else if(collision.gameObject.tag == "Enemy"){
                SceneManager.LoadScene("Game_0");
            }
    

        }

    void Awake()
 {
    rigid = GetComponent<Rigidbody2D>();
    sprend = GetComponent<SpriteRenderer>();
    anim = GetComponent<Animator>();
    item_audio = GetComponent<AudioSource>();

 }
    void FixedUpdate()
 {
     float h = Input.GetAxisRaw("Horizontal");
     rigid.AddForce(Vector2.right * h, ForceMode2D.Impulse);
        if (rigid.velocity.x > maxSpeed) // 오른쪽이동
            rigid.velocity = new Vector2(maxSpeed, rigid.velocity.y);
        else if (rigid.velocity.x < -1 * maxSpeed) //왼쪽이동
            rigid.velocity = new Vector2(-1 * maxSpeed, rigid.velocity.y);
 }

 private void Update()
 {

 if ((Input.GetButtonDown("Jump") && !(anim.GetBool("isJump"))))
    rigid.AddForce(Vector2.up * jumpPower, ForceMode2D.Impulse);
    

 if (Input.GetButtonUp("Horizontal"))
 {
 rigid.velocity = new Vector2(rigid.velocity.normalized.x * 0.5f, rigid.velocity.y);
 }
 if (Input.GetButtonDown("Horizontal") && !anim.GetBool("isJump"))
 {
 sprend.flipX = Input.GetAxisRaw("Horizontal") < 0; // 방향전환 값이 음수로 바뀜
 }
 else if (anim.GetBool("isJump"))
 {
 sprend.flipX = Input.GetAxisRaw("Horizontal") > 0;
 }

 if (Input.GetButtonDown("Jump") && !anim.GetBool("isJump") )
 {
 
 rigid.AddForce( Vector2.up * 7, ForceMode2D.Impulse);
 
 }


 if (rigid.velocity.normalized.x == 0) // 속도 단위벡터 값이0 이면 정지
 {
 anim.SetBool("isRun", false);
 }
 else
 {
 anim.SetBool("isRun", true);
 }

 if (Mathf.Abs(rigid.velocity.x) < 0.1) // 속도 값이 0에 가까우면 isRun false
 anim.SetBool("isRun", false);
 else
 anim.SetBool("isRun", true);
 if (Input.GetButtonDown("Jump")) {
 rigid.AddForce(Vector2.up * jumpPower, ForceMode2D.Impulse);
 // anim.SetBool("isJump", true);
 }
 else
 // anim.SetBool("isJump", false);
 if (Mathf.Abs(rigid.velocity.y) < 0.1) // 속도 값이 0에 가까우면 isJump false
 anim.SetBool("isJump", false);
 else
 anim.SetBool("isJump", true);
 Debug.Log("isRun= " + anim.GetBool("isRun") + " isJump= " + anim.GetBool("isJump"));


}

}