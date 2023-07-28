package com.example.board20.controller;

import com.example.board20.dto.BoardDto;
import com.example.board20.service.BoardService;
import lombok.extern.slf4j.Slf4j;
import org.springframework.data.domain.Page;
import org.springframework.data.domain.Pageable;
import org.springframework.data.domain.Sort;
import org.springframework.data.web.PageableDefault;
import org.springframework.stereotype.Controller;
import org.springframework.ui.Model;
import org.springframework.web.bind.annotation.*;
import org.springframework.web.multipart.MultipartFile;
import org.springframework.web.servlet.config.annotation.ResourceHandlerRegistry;

import java.io.File;
import java.io.IOException;
import java.util.List;

@Slf4j
@Controller
public class BoardController {

    private BoardService boardService;

    public BoardController(BoardService boardService) {
        this.boardService = boardService;
    }


    @GetMapping("/")
    public String list(Model model, @PageableDefault(page = 0, size = 10, sort = "id", direction = Sort.Direction.DESC) Pageable pageable) {
        Page<BoardDto> boardDtoList = boardService.getBoardList(pageable);

        int nowPage = boardDtoList.getPageable().getPageNumber() + 1;
        int totalBoards = (int) boardDtoList.getTotalElements();
        int pageSize = boardDtoList.getSize();

        // Calculate the start and end page numbers to display
        int totalPages = (int) Math.ceil((double) totalBoards / pageSize);
        int maxVisiblePages = 5; // You can adjust this to change the number of visible pages
        int startPage = Math.max(nowPage - maxVisiblePages / 2, 1);
        int endPage = Math.min(startPage + maxVisiblePages - 1, totalPages);

        // Adjust endPage to ensure it is within the valid range of page numbers
        if (endPage - startPage + 1 < maxVisiblePages) {
            endPage = Math.min(startPage + maxVisiblePages - 1, totalPages);
            startPage = Math.max(endPage - maxVisiblePages + 1, 1);
        }

        List<BoardDto> paginatedBoards = boardDtoList.getContent();
        long boardNumber = totalBoards - (boardDtoList.getNumber() * boardDtoList.getSize());
        for (BoardDto board : paginatedBoards) {
            board.setBoardNumber(boardNumber);
            boardNumber--;
        }



        model.addAttribute("postList", paginatedBoards);
        model.addAttribute("nowPage", nowPage);
        model.addAttribute("startPage", startPage);
        model.addAttribute("endPage", endPage);

        return "board/list.html";
    }

    @GetMapping("/post")
    public String post() {
        return "board/post.html";
    }


    @PostMapping("/post")
    public String write(@ModelAttribute BoardDto boardDto, @RequestParam("uploadFile") MultipartFile uploadFile) {
        log.info("1-uploadFile(send)={}", uploadFile.getOriginalFilename());


        String originalFileName = uploadFile.getOriginalFilename();
        //test2를 프로젝트 이름에 맞게 변경
        String savedFilePath = "D:\\uploads\\" + originalFileName; // Change this to your desired file path
        log.info("2-savedFilePath(send)={}", savedFilePath);
        // savedFilePath에 파일 저장
        try {
            uploadFile.transferTo(new File(savedFilePath));
        } catch (IOException e) {
            // 예외처리
        }

        boardDto.setOriginalFileName(originalFileName);
        boardDto.setSavedFilePath(savedFilePath);

        boardService.savePost(boardDto, uploadFile);

        return "redirect:/";
    }

    @GetMapping("/post/{id}")
    public String detail(@PathVariable("id") Long id, Model model) {
        BoardDto boardDto = boardService.getPost(id);
        log.info("3-uploadFile(receive)={}", boardDto.getOriginalFileName());
        log.info("4-uploadFile(receive)={}", boardDto.getSavedFilePath());

        model.addAttribute("post", boardDto);

        String path = "file:///" + boardDto.getSavedFilePath();

        log.info("5-uploadFile(checking)={}", path);

        model.addAttribute("fileName", boardDto.getOriginalFileName());
        model.addAttribute("filePath", path);


        return "board/detail.html";
    }

    @GetMapping("/post/edit/{id}")
    public String edit(@PathVariable("id") Long id, Model model) {
        BoardDto boardDto = boardService.getPost(id);
        model.addAttribute("post", boardDto);

        // Retain image information for the edit page
        model.addAttribute("fileName", boardDto.getOriginalFileName());
        model.addAttribute("filePath", boardDto.getSavedFilePath());

        return "board/edit.html";
    }

    @PutMapping("/post/edit/{id}")
    public String update(@PathVariable("id") Long id, @ModelAttribute BoardDto updatedBoardDto,
                         @RequestParam(value = "uploadFile", required = false) MultipartFile uploadFile) {
        BoardDto existingBoardDto = boardService.getPost(id);

        // Retain the existing image information if no new file is uploaded
        if (uploadFile == null || uploadFile.isEmpty()) {
            updatedBoardDto.setOriginalFileName(existingBoardDto.getOriginalFileName());
            updatedBoardDto.setSavedFilePath(existingBoardDto.getSavedFilePath());
        } else {
            // If a new file is uploaded, update the image information
            String originalFileName = uploadFile.getOriginalFilename();
            String savedFilePath = "D:\\uploads\\" + originalFileName;
            updatedBoardDto.setOriginalFileName(originalFileName);
            updatedBoardDto.setSavedFilePath(savedFilePath);
            try {
                uploadFile.transferTo(new File(savedFilePath));
            } catch (IOException e) {
                // Handle the exception
            }
        }

        // Update other fields
        existingBoardDto.setAuthor(updatedBoardDto.getAuthor());
        existingBoardDto.setTitle(updatedBoardDto.getTitle());
        existingBoardDto.setContent(updatedBoardDto.getContent());

        // Save the updated post, including the image information
        boardService.savePost(existingBoardDto, uploadFile);

        return "redirect:/post/" + id;
    }

    @DeleteMapping("/post/{id}")
    public String delete(@PathVariable("id") Long id) {
        boardService.deletePost(id);
        return "redirect:/";
    }


    @GetMapping("/board/search")
    public String search(@RequestParam("keyword") String keyword, Model model,
                         @PageableDefault(page = 0, size = 500, sort = "id",
                                 direction = Sort.Direction.DESC) Pageable pageable) {


        Page<BoardDto> searchResultPage = boardService.search(keyword, pageable);
        List<BoardDto> searchList = searchResultPage.getContent();

        int nowPage = searchResultPage.getPageable().getPageNumber() + 1;
        int totalBoards = (int) searchResultPage.getTotalElements();
        int pageSize = searchResultPage.getSize();

        // Calculate the start and end page numbers to display
        int totalPages = (int) Math.ceil((double) totalBoards / pageSize);
        int maxVisiblePages = 5; // You can adjust this to change the number of visible pages
        int startPage = Math.max(nowPage - maxVisiblePages / 2, 1);
        int endPage = Math.min(startPage + maxVisiblePages - 1, totalPages);

        // Adjust endPage to ensure it is within the valid range of page numbers
        if (endPage - startPage + 1 < maxVisiblePages) {
            endPage = Math.min(startPage + maxVisiblePages - 1, totalPages);
            startPage = Math.max(endPage - maxVisiblePages + 1, 1);
        }


        model.addAttribute("postList", searchList);
        model.addAttribute("nowPage", nowPage);
        model.addAttribute("startPage", startPage);
        model.addAttribute("endPage", endPage);


        return "board/searchPage";
    }


}
